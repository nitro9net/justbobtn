<?php namespace Nitro9net\BlogPhotos\Models;

use Backend\Models\ImportModel;
use Exception;
use Illuminate\Support\Arr;
use RainLab\Blog\Models\Post as BlogPost;
use System\Models\File;

class GalleryImport extends ImportModel
{
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {
            try {
                $post = $this->findPost($data);

                if (!$post) {
                    $this->logSkipped($row, 'No matching blog post found. Provide post_slug or post_id.');
                    continue;
                }

                $file = $this->makeFile($data);

                if (!$file) {
                    $this->logSkipped($row, 'No photo source found. Provide disk_name, file_path, or url.');
                    continue;
                }

                $post->gallery_images()->add($file);
                $this->logCreated();
            }
            catch (Exception $exception) {
                $this->logError($row, $exception->getMessage());
            }
        }
    }

    protected function findPost(array $data): ?BlogPost
    {
        if ($postId = Arr::get($data, 'post_id')) {
            return BlogPost::find($postId);
        }

        if ($postSlug = Arr::get($data, 'post_slug')) {
            return BlogPost::where('slug', $postSlug)->first();
        }

        return null;
    }

    protected function makeFile(array $data): ?File
    {
        $file = new File;
        $file->field = 'gallery_images';
        $file->attachment_type = BlogPost::class;
        $file->file_name = Arr::get($data, 'file_name') ?: basename((string) Arr::get($data, 'disk_name'));
        $file->title = Arr::get($data, 'title');
        $file->description = Arr::get($data, 'description');
        $file->sort_order = (int) Arr::get($data, 'sort_order', 0);
        $file->is_public = $this->normalizeBoolean(Arr::get($data, 'is_public', true));

        if ($contentType = Arr::get($data, 'content_type')) {
            $file->content_type = $contentType;
        }

        if ($fileSize = Arr::get($data, 'file_size')) {
            $file->file_size = (int) $fileSize;
        }

        if ($diskName = Arr::get($data, 'disk_name')) {
            $file->disk_name = $diskName;

            if (!$file->file_name) {
                $file->file_name = basename($diskName);
            }

            $file->save();

            return $file;
        }

        if ($filePath = Arr::get($data, 'file_path')) {
            $file->data = $filePath;
            $file->save();

            return $file;
        }

        return null;
    }

    protected function normalizeBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'y'], true);
    }
}
