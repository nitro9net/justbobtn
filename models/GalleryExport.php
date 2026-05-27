<?php namespace Nitro9net\BlogPhotos\Models;

use Backend\Models\ExportModel;
use RainLab\Blog\Models\Post as BlogPost;

class GalleryExport extends ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $rows = [];

        BlogPost::with('gallery_images')->orderBy('id')->chunk(100, function ($posts) use (&$rows) {
            foreach ($posts as $post) {
                foreach ($post->gallery_images as $photo) {
                    $rows[] = [
                        'post_id' => $post->id,
                        'post_slug' => $post->slug,
                        'post_title' => $post->title,
                        'photo_id' => $photo->id,
                        'disk_name' => $photo->disk_name,
                        'file_name' => $photo->file_name,
                        'file_size' => $photo->file_size,
                        'content_type' => $photo->content_type,
                        'title' => $photo->title,
                        'description' => $photo->description,
                        'sort_order' => $photo->sort_order,
                        'is_public' => $photo->is_public,
                        'path' => $photo->path,
                        'field' => $photo->field,
                        'attachment_type' => $photo->attachment_type,
                        'attachment_id' => $photo->attachment_id
                    ];
                }
            }
        });

        return $rows;
    }
}
