<?php namespace Nitro9net\BlogPhotos\Components;

use Cms\Classes\ComponentBase;
use stdClass;
use RainLab\Blog\Models\Post as BlogPost;
use System\Models\File;

class RandomPhoto extends ComponentBase
{
    public $photo;
    public $post;
    public $random;

    public function componentDetails()
    {
        return [
            'name' => 'nitro9net.blogphotos::lang.components.random_photo.name',
            'description' => 'nitro9net.blogphotos::lang.components.random_photo.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'thumbWidth' => [
                'title' => 'nitro9net.blogphotos::lang.properties.thumb_width.title',
                'default' => 480,
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'nitro9net.blogphotos::lang.properties.numeric_validation'
            ],
            'thumbHeight' => [
                'title' => 'nitro9net.blogphotos::lang.properties.thumb_height.title',
                'default' => 320,
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'nitro9net.blogphotos::lang.properties.numeric_validation'
            ],
            'linkTo' => [
                'title' => 'nitro9net.blogphotos::lang.properties.link_to.title',
                'description' => 'nitro9net.blogphotos::lang.properties.link_to.description',
                'default' => 'image',
                'type' => 'dropdown',
                'options' => [
                    'image' => 'Image',
                    'post' => 'Blog post',
                    'none' => 'No link'
                ]
            ],
            'postPage' => [
                'title' => 'nitro9net.blogphotos::lang.properties.post_page.title',
                'description' => 'nitro9net.blogphotos::lang.properties.post_page.description',
                'default' => 'blog/post',
                'type' => 'string'
            ],
            'includeCss' => [
                'title' => 'nitro9net.blogphotos::lang.properties.include_css.title',
                'description' => 'nitro9net.blogphotos::lang.properties.include_css.description',
                'default' => true,
                'type' => 'checkbox'
            ]
        ];
    }

    public function onRun()
    {
        if ($this->property('includeCss')) {
            $this->addCss('/plugins/nitro9net/blogphotos/assets/css/random-photo.css');
        }

        $this->photo = $this->loadRandomPhoto();
        $this->post = $this->loadPhotoPost();
        $this->random = $this->page['galleryRandom'] = $this->makeGalleryRandom();

        $this->page['randomBlogPhoto'] = $this->photo;
        $this->page['randomBlogPhotoPost'] = $this->post;
    }

    public function postUrl(): ?string
    {
        if (!$this->post) {
            return null;
        }

        return $this->controller->pageUrl($this->property('postPage'), [
            'slug' => $this->post->slug
        ]);
    }

    protected function loadRandomPhoto(): ?File
    {
        $postIds = $this->publishedPostQuery()->pluck('id')->all();

        if (!$postIds) {
            return null;
        }

        return File::where('attachment_type', BlogPost::class)
            ->where('field', 'gallery_images')
            ->whereIn('attachment_id', $postIds)
            ->inRandomOrder()
            ->first();
    }

    protected function loadPhotoPost(): ?BlogPost
    {
        if (!$this->photo) {
            return null;
        }

        return BlogPost::where('id', $this->photo->attachment_id)->first();
    }

    protected function makeGalleryRandom(): ?stdClass
    {
        if (!$this->photo) {
            return null;
        }

        $thumbWidth = (int) $this->property('thumbWidth');
        $thumbHeight = (int) $this->property('thumbHeight');
        $postUrl = $this->postUrl();

        $random = new stdClass;
        $random->photo = $this->photo;
        $random->post = $this->post;
        $random->path = $this->photo->path;
        $random->thumb = $this->photo->getThumb($thumbWidth, $thumbHeight, ['mode' => 'crop']);
        $random->postUrl = $postUrl;
        $random->caption = $this->photo->description ?: ($this->photo->title ?: ($this->post ? $this->post->title : null));
        $random->alt = $this->photo->title ?: $this->photo->file_name;

        return $random;
    }

    protected function publishedPostQuery()
    {
        $query = BlogPost::query();

        if (method_exists(BlogPost::class, 'scopeIsPublished')) {
            return $query->isPublished();
        }

        return $query->where('published', true);
    }
}
