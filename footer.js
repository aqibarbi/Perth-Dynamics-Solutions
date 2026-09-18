/**
 * Header / Footer JS
 * Handles: scroll-to-top progress circle, mobile menu toggle.
 * Wrapped in DOMContentLoaded so it's safe to load in <head> too,
 * but best practice is to enqueue this in the footer (see functions.php).
 */
document.addEventListener('DOMContentLoaded', function () {

    // Scroll-to-top progress ring
    window.addEventListener('scroll', () => {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = (scrollTop / docHeight) * 100;
        const wrap = document.getElementById('progressWrap');
        const ring = document.getElementById('progressRing');

        if (!wrap || !ring) return;

        ring.style.background =
            `conic-gradient(#ff7a18 ${progress}%, #eee ${progress}%)`;

        if (scrollTop > 50) {
            wrap.classList.add('show');
        } else {
            wrap.classList.remove('show');
        }
    });


});

