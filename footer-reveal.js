/**
 * Footer Scroll Reveal
 * Fades/lifts footer sections (reviews, contact box) into view on scroll.
 * Same technique as the main content reveal, scoped to footer-only elements
 * so it doesn't double up with the main.js reveal observer.
 */
document.addEventListener('DOMContentLoaded', function () {

    const prefersReduced = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches;

    const targets = document.querySelectorAll(
        ['.review-card', '.foot-contact-box'].join(',')
    );

    if (!targets.length || prefersReduced || !('IntersectionObserver' in window)) {
        return;
    }

    targets.forEach((el, i) => {
        el.classList.add('reveal');
        el.style.transitionDelay = (i % 6) * 70 + 'ms';
    });

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
    );

    targets.forEach((el) => io.observe(el));

});
