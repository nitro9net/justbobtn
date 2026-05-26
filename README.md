# BlogPhotos for October CMS

Adds a Gallery tab to RainLab Blog posts and a frontend component for displaying the uploaded images.

## Requirements

- October CMS 4.2
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

The component exposes `galleryRandom` to the page if you want to build your own markup. It includes `photo`, `post`, `path`, `thumb`, `postUrl`, `caption`, and `alt`.

### Custom partial

An example October CMS partial is included at:

```text
plugins/nitro9net/blogphotos/examples/partials/random-blog-photo.htm
```

Copy it to your active theme's `partials` directory, then use it on a page where the `randomBlogPhoto` component is attached:

```twig
{% partial 'random-blog-photo' %}
```
