# Random Blog Photo Component

Displays one smart-cropped random photo from any published RainLab Blog post that has Gallery images.

## CMS Page Example

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

The component exposes `galleryRandom` to the page for custom markup.

```twig
{% if galleryRandom %}
    <a href="{{ galleryRandom.url ?: galleryRandom.path }}">
        <img src="{{ galleryRandom.thumb }}" alt="{{ galleryRandom.alt }}">
    </a>
{% endif %}
```
