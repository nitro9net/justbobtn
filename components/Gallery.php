<?php namespace Nitro9net\BlogPhotos\Components;

use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;

class Gallery extends ComponentBase
{
    public $post;
    public $images;

    public function componentDetails()
    {
        return [
            'name' => 'nitro9net.blogphotos::lang.components.gallery.name',
            'description' => 'nitro9net.blogphotos::lang.components.gallery.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => 'nitro9net.blogphotos::lang.properties.slug.title',
                'description' => 'nitro9net.blogphotos::lang.properties.slug.description',
                'default' => '{{ :slug }}',
                'type' => 'string'
            ],
            'postVariable' => [
                'title' => 'nitro9net.blogphotos::lang.properties.post_variable.title',
                'description' => 'nitro9net.blogphotos::lang.properties.post_variable.description',
                'default' => 'post',
                'type' => 'string'
            ],
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
            'thumbMode' => [
                'title' => 'nitro9net.blogphotos::lang.properties.thumb_mode.title',
                'default' => 'crop',
                'type' => 'dropdown',
                'options' => [
                    'crop' => 'Smart crop'
                ]
            ],
            'includeCss' => [
                'title' => 'nitro9net.blogphotos::lang.properties.include_css.title',
                'description' => 'nitro9net.blogphotos::lang.properties.include_css.description',
                'default' => true,
                'type' => 'checkbox'
            ],
            'includeJs' => [
                'title' => 'nitro9net.blogphotos::lang.properties.include_js.title',
                'description' => 'nitro9net.blogphotos::lang.properties.include_js.description',
                'default' => true,
                'type' => 'checkbox'
            ]
        ];
    }

    public function onRun()
    {
        if ($this->property('includeCss')) {
            $this->addCss('/plugins/nitro9net/blogphotos/assets/css/gallery.css');
        }

        if ($this->property('includeJs')) {
            $this->addJs('/plugins/nitro9net/blogphotos/assets/js/gallery-lightbox.js');
        }

        $this->post = $this->page['galleryPost'] = $this->loadPost();
        $this->images = $this->page['galleryImages'] = $this->loadImages();
    }

    protected function loadPost(): ?BlogPost
    {
        $postVariable = trim((string) $this->property('postVariable'));

        if ($postVariable && ($post = $this->page[$postVariable] ?? null)) {
            if ($post instanceof BlogPost) {
                return $post;
            }
        }

        $slug = trim((string) $this->property('slug'));

        if (!$slug) {
            return null;
        }

        return BlogPost::where('slug', $slug)->first();
    }

    protected function loadImages()
    {
        if (!$this->post) {
            return collect();
        }

        return $this->post->gallery_images()->orderBy('sort_order')->get();
    }
}
