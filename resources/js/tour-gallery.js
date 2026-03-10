import GLightbox from 'glightbox';
import 'glightbox/dist/css/glightbox.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const hasGallery = document.querySelector('.tour-gallery, .hotel-gallery, .city-gallery');
    if (hasGallery) {
        GLightbox({
            selector: '.tour-gallery .glightbox, .hotel-gallery .glightbox, .city-gallery .glightbox',
            touchNavigation: true,
            loop: true,
            autoplayVideos: true,
        });
    }
});
