import './bootstrap';
import 'lite-youtube-embed';

// Fades gauche/droite sur les scrolls horizontaux avec transition opacity
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.cast-scroll').forEach((el) => {
        // Injection du wrapper
        const wrap = document.createElement('div');
        wrap.className = 'cast-scroll-wrap';
        el.parentNode.insertBefore(wrap, el);
        wrap.appendChild(el);

        const update = () => {
            const atStart = el.scrollLeft <= 4;
            const atEnd   = el.scrollLeft + el.clientWidth >= el.scrollWidth - 4;
            wrap.classList.toggle('cast-scroll-wrap--fade-left',  !atStart);
            wrap.classList.toggle('cast-scroll-wrap--fade-right', !atEnd);
        };

        update();
        el.addEventListener('scroll', update, { passive: true });
    });
});
