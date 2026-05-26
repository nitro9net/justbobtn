<?php

return [
    'plugin' => [
        'name' => 'BlogPhotos',
        'description' => 'Adds image galleries to RainLab Blog posts.'
    ],
    'fields' => [
        'gallery_images' => 'Gallery images',
        'gallery_images_comment' => 'Upload and reorder images for this blog post.'
    ],
    'tabs' => [
        'gallery' => 'Gallery'
    ],
    'components' => [
        'gallery' => [
            'name' => 'BlogPhotos Gallery',
            'description' => 'Displays the image gallery attached to a blog post.'
        ],
        'random_photo' => [
            'name' => 'Random Blog Photo',
            'description' => 'Displays a random gallery image from any published blog post.'
        ]
    ],
    'properties' => [
        'slug' => [
            'title' => 'Post slug',
            'description' => 'Blog post slug used when no blog post variable is available.'
        ],
        'post_variable' => [
            'title' => 'Post variable',
            'description' => 'Page variable containing the RainLab Blog post model.'
        ],
        'thumb_width' => [
            'title' => 'Thumbnail width'
        ],
        'thumb_height' => [
            'title' => 'Thumbnail height'
        ],
        'thumb_mode' => [
            'title' => 'Thumbnail mode'
        ],
        'include_css' => [
            'title' => 'Include CSS',
            'description' => 'Adds the default gallery stylesheet to the page.'
        ],
        'include_js' => [
            'title' => 'Include JavaScript',
            'description' => 'Adds the default lightbox script to the page.'
        ],
        'link_to' => [
            'title' => 'Link to',
            'description' => 'Choose what happens when the random photo is clicked.'
        ],
        'post_page' => [
            'title' => 'Post page',
            'description' => 'CMS page used to build blog post links.'
        ],
        'numeric_validation' => 'Please enter a whole number.'
    ]
];
