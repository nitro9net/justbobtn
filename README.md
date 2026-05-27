# BlogPhotos for October CMS

Adds a Gallery tab to RainLab Blog posts and a frontend component for displaying the uploaded images.

## Requirements

- October CMS 4.2+
- PHP 8.2+
- RainLab Blog

## Installation

Copy this plugin to:

```text
plugins/nitro9net/blogphotos
```

Then refresh October CMS plugins from the backend or run:

```bash
php artisan october:migrate
```

For October CMS v4 projects, remove legacy Flysystem v1 adapters from the root project if you see `Class "League\Flysystem\Adapter\AbstractAdapter" not found`. That error comes from an old storage adapter package, not from BlogPhotos. Check with:

```bash
composer show | grep -i flysystem
composer why league/flysystem
```

If you install with Composer using a local path repository, make sure the path exists relative to the October project root before running `composer require`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./plugins/nitro9net/blogphotos"
        }
    ]
}
```

Composer will throw `The url supplied for the path repository does not exist` when that folder has not been copied into place or when Composer is run from a different directory.

## Usage

1. Open a RainLab Blog post in the backend.
2. Use the Gallery tab to upload and reorder images.
3. Add the component to your blog post page after the `blogPost` component.

```ini
[blogPost]
slug = "{{ :slug }}"

[blogGallery]
slug = "{{ :slug }}"
postVariable = "post"
thumbWidth = 480
thumbHeight = 320
thumbMode = "crop"
includeCss = true
includeJs = true
```

```twig
{% component 'blogPost' %}
{% component 'blogGallery' %}
```

The component exposes `galleryPost` and `galleryImages` to the page if you want to build your own markup.

The default component markup shows equal-size smart-cropped thumbnails. Clicking a thumbnail opens a JavaScript lightbox with previous, next, and close controls. Keyboard users can use Escape, Left Arrow, and Right Arrow inside the lightbox.

## Random photo

Use the `randomBlogPhoto` component to show one smart-cropped random image from any published blog post that has gallery images.

```ini
[randomBlogPhoto]
thumbWidth = 480
thumbHeight = 320
linkTo = "image"
postPage = "blog/post"
includeCss = true
```

```twig
{% component 'randomBlogPhoto' %}
```

Set `linkTo` to `image`, `post`, or `none`. When linking to a post, set `postPage` to the CMS page used for blog post details.

The component exposes `galleryRandom` to the page if you want to build your own markup. It includes `photo`, `post`, `linkTo`, `url`, `path`, `thumb`, `postUrl`, `caption`, and `alt`.

`galleryRandom.url` follows the Random photo backend setting for `Link to`. Use `galleryRandom.linkTo` if you need to inspect the selected mode.

## Backend management

BlogPhotos adds a backend navigation item at `BlogPhotos > Galleries`. From there you can view blog posts that have gallery photos, edit gallery images and captions, delete individual photos with the file upload widget, or clear entire galleries.

Default component settings are available in October's backend Settings area under `BlogPhotos`.

## CSV import and export

Use `BlogPhotos > Galleries > Import CSV` to import photos from another gallery program. Each row should identify a blog post using `post_slug` or `post_id`, then provide one photo source:

```csv
post_slug,disk_name,file_name,title,description,sort_order,is_public
my-blog-post,650f1d2a8a1f9001234567.jpg,my-photo.jpg,Photo title,Photo caption,1,1
```

Supported import columns include `post_slug`, `post_id`, `disk_name`, `file_path`, `file_name`, `title`, `description`, `sort_order`, `is_public`, `file_size`, and `content_type`.

Use `Export CSV` to download the current BlogPhotos galleries, including post data, photo metadata, `disk_name`, path, `field`, and `attachment_type`.

### Custom partial

An example October CMS partial is included at:

```text
plugins/nitro9net/blogphotos/examples/partials/random-blog-photo.htm
```

Copy it to your active theme's `partials` directory, then use it on a page where the `randomBlogPhoto` component is attached:

```twig
{% partial 'random-blog-photo' %}
```
