# BlogPhotos Gallery Component

Displays images attached to the current RainLab Blog post.

## CMS Page Example

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

When used after RainLab Blog's `blogPost` component, this component will use the existing `post` page variable. If that variable is not available, it falls back to loading a blog post by slug.

The default markup renders equal-size smart-cropped thumbnails that open in a JavaScript lightbox. The lightbox includes previous, next, and close buttons, plus Escape, Left Arrow, and Right Arrow keyboard support.
