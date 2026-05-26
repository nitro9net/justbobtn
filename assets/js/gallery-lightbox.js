(function () {
    function initGallery(gallery) {
        var lightbox = gallery.nextElementSibling;

        if (!lightbox || !lightbox.matches('[data-gallery-lightbox]')) {
            return;
        }

        var items = Array.prototype.slice.call(gallery.querySelectorAll('[data-gallery-item]'));
        var image = lightbox.querySelector('[data-gallery-lightbox-image]');
        var caption = lightbox.querySelector('[data-gallery-lightbox-caption]');
        var closeButton = lightbox.querySelector('[data-gallery-close]');
        var prevButton = lightbox.querySelector('[data-gallery-prev]');
        var nextButton = lightbox.querySelector('[data-gallery-next]');
        var activeIndex = 0;

        function showImage(index) {
            if (!items.length) {
                return;
            }

            activeIndex = (index + items.length) % items.length;

            var item = items[activeIndex];
            var src = item.getAttribute('data-gallery-src');
            var text = item.getAttribute('data-gallery-caption') || '';

            image.src = src;
            image.alt = item.querySelector('img') ? item.querySelector('img').alt : text;
            caption.textContent = text;
            caption.hidden = !text;
        }

        function openLightbox(index) {
            showImage(index);
            lightbox.hidden = false;
            lightbox.setAttribute('aria-hidden', 'false');
            document.documentElement.classList.add('blog-gallery-lightbox-open');
            closeButton.focus();
        }

        function closeLightbox() {
            lightbox.hidden = true;
            lightbox.setAttribute('aria-hidden', 'true');
            document.documentElement.classList.remove('blog-gallery-lightbox-open');
            image.removeAttribute('src');
            items[activeIndex].focus();
        }

        items.forEach(function (item, index) {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                openLightbox(index);
            });
        });

        closeButton.addEventListener('click', closeLightbox);

        prevButton.addEventListener('click', function () {
            showImage(activeIndex - 1);
        });

        nextButton.addEventListener('click', function () {
            showImage(activeIndex + 1);
        });

        lightbox.addEventListener('click', function (event) {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (lightbox.hidden) {
                return;
            }

            if (event.key === 'Escape') {
                closeLightbox();
            }

            if (event.key === 'ArrowLeft') {
                showImage(activeIndex - 1);
            }

            if (event.key === 'ArrowRight') {
                showImage(activeIndex + 1);
            }
        });
    }

    function init() {
        Array.prototype.forEach.call(document.querySelectorAll('[data-blog-gallery]'), initGallery);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
