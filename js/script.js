/* js/script.js
   Adds `in-view` to elements with these classes when they enter the viewport:
     - .animate-fade-in
     - .animate-slide-up
   Supports delay classes like `delay-1`, `delay-2` (multiplies 200ms per step).
   Respects prefers-reduced-motion and provides a scroll fallback for browsers
   without IntersectionObserver.
*/

document.addEventListener('DOMContentLoaded', function () {
  const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const selector = '.animate-fade-in, .animate-slide-up';
  const elems = Array.prototype.slice.call(document.querySelectorAll(selector));

  // If user prefers reduced motion, immediately mark everything as visible.
  if (prefersReducedMotion) {
    elems.forEach(el => el.classList.add('in-view'));
    return;
  }

  // Helper: look for classes like delay-1, delay-2 and set transition-delay accordingly
  function applyDelay(el) {
    for (let i = 0; i < el.classList.length; i++) {
      const c = el.classList[i];
      const m = c.match(/^delay-(\d+)$/);
      if (m) {
        const n = parseInt(m[1], 10);
        // Each step equals 200ms (tunable)
        el.style.transitionDelay = (n * 200) + 'ms';
        break;
      }
    }
  }

  // IntersectionObserver approach for modern browsers
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const el = entry.target;
          applyDelay(el);
          el.classList.add('in-view');
          observer.unobserve(el); // animate only once
        }
      });
    }, {
      root: null,
      rootMargin: '0px 0px -10% 0px', // trigger a bit before element fully in view
      threshold: 0.12
    });

    elems.forEach(el => io.observe(el));
    return;
  }

  // Fallback for browsers without IntersectionObserver
  function isInViewport(el, offset = 0.12) {
    const rect = el.getBoundingClientRect();
    return rect.top < (window.innerHeight * (1 - offset));
  }

  function onScrollFallback() {
    elems.forEach(el => {
      if (el.classList.contains('in-view')) return;
      if (isInViewport(el)) {
        applyDelay(el);
        el.classList.add('in-view');
      }
    });
  }

  // Basic throttle to avoid heavy scroll work
  function throttle(fn, wait) {
    let last = 0;
    return function () {
      const now = Date.now();
      if (now - last >= wait) {
        last = now;
        fn();
      }
    };
  }

  // Run once to catch elements already on screen
  onScrollFallback();
  window.addEventListener('scroll', throttle(onScrollFallback, 150));
  window.addEventListener('resize', throttle(onScrollFallback, 200));
});
