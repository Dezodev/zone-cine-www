import './bootstrap';
import 'lite-youtube-embed';

// Menu mobile
document.addEventListener('DOMContentLoaded', () => {
    const btn     = document.getElementById('mobile-menu-btn');
    const menu    = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');
    const closeBtn = document.getElementById('mobile-menu-close');

    if (!btn || !menu || !overlay) return;

    const open = () => {
        menu.classList.add('is-open');
        overlay.classList.add('is-open');
        btn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    };

    const close = () => {
        menu.classList.remove('is-open');
        overlay.classList.remove('is-open');
        btn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    };

    btn.addEventListener('click', open);
    overlay.addEventListener('click', close);
    if (closeBtn) closeBtn.addEventListener('click', close);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && menu.classList.contains('is-open')) close();
    });
});

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
