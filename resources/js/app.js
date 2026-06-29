import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

/* ==========================================================================
   Interactive 3D & motion layer (dependency-free)
   - [data-tilt]      : card rotates toward the cursor (3D tilt)
   - [data-parallax]  : element drifts subtly with the pointer
   - .reveal          : fades/slides in when scrolled into view
   ========================================================================== */
(() => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---- Scroll reveal -------------------------------------------------- */
    const revealEls = document.querySelectorAll('.reveal');
    if (revealEls.length) {
        if (reduceMotion || !('IntersectionObserver' in window)) {
            revealEls.forEach((el) => el.classList.add('is-visible'));
        } else {
            const io = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            io.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
            );
            revealEls.forEach((el) => io.observe(el));
        }
    }

    if (reduceMotion) return;

    /* ---- 3D tilt toward cursor ----------------------------------------- */
    const tiltEls = document.querySelectorAll('[data-tilt]');
    tiltEls.forEach((el) => {
        const max = parseFloat(el.dataset.tilt) || 10; // max degrees
        const onMove = (e) => {
            const r = el.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width - 0.5;
            const py = (e.clientY - r.top) / r.height - 0.5;
            el.style.setProperty('--rx', `${px * max}deg`);
            el.style.setProperty('--ry', `${-py * max}deg`);
        };
        const reset = () => {
            el.style.setProperty('--rx', '0deg');
            el.style.setProperty('--ry', '0deg');
        };
        el.addEventListener('mousemove', onMove);
        el.addEventListener('mouseleave', reset);
    });

    /* ---- Pointer parallax (hero scene drift) --------------------------- */
    const parallaxEls = document.querySelectorAll('[data-parallax]');
    if (parallaxEls.length) {
        let ticking = false;
        window.addEventListener('mousemove', (e) => {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(() => {
                const cx = e.clientX / window.innerWidth - 0.5;
                const cy = e.clientY / window.innerHeight - 0.5;
                parallaxEls.forEach((el) => {
                    const depth = parseFloat(el.dataset.parallax) || 20;
                    el.style.transform = `translate3d(${cx * depth}px, ${cy * depth}px, 0)`;
                });
                ticking = false;
            });
        });
    }
})();
