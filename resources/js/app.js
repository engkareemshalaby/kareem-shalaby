document.querySelectorAll('[data-menu-toggle]').forEach((button)=>{button.addEventListener('click',()=>document.querySelector('[data-mobile-menu]')?.classList.toggle('hidden'))});

const adminSidebar = document.querySelector('[data-admin-sidebar]');
const adminBackdrop = document.querySelector('[data-admin-backdrop]');
const openAdminSidebar = () => {
    adminSidebar?.classList.remove('translate-x-full');
    adminBackdrop?.classList.remove('hidden');
};
const closeAdminSidebar = () => {
    adminSidebar?.classList.add('translate-x-full');
    adminBackdrop?.classList.add('hidden');
};

document.querySelector('[data-admin-menu-open]')?.addEventListener('click', openAdminSidebar);
document.querySelector('[data-admin-menu-close]')?.addEventListener('click', closeAdminSidebar);
adminBackdrop?.addEventListener('click', closeAdminSidebar);

const homePage = document.querySelector('.home-page');
if (homePage) {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const revealElements = homePage.querySelectorAll('[data-home-reveal]');

    if (reducedMotion.matches || !('IntersectionObserver' in window)) {
        revealElements.forEach((element) => element.classList.add('is-visible'));
    } else {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px 40px 0px' });
        revealElements.forEach((element) => observer.observe(element));
    }

    const hero = homePage.querySelector('[data-home-hero]');
    const art = homePage.querySelector('[data-home-art]');
    if (hero && art && window.matchMedia('(hover: hover) and (pointer: fine)').matches && !reducedMotion.matches) {
        hero.addEventListener('pointermove', (event) => {
            const bounds = hero.getBoundingClientRect();
            const x = (event.clientX - bounds.left) / bounds.width - 0.5;
            const y = (event.clientY - bounds.top) / bounds.height - 0.5;
            art.style.setProperty('--home-tilt-x', `${-y * 7}deg`);
            art.style.setProperty('--home-tilt-y', `${x * 7}deg`);
        });
        hero.addEventListener('pointerleave', () => {
            art.style.setProperty('--home-tilt-x', '0deg');
            art.style.setProperty('--home-tilt-y', '0deg');
        });
    }
}
