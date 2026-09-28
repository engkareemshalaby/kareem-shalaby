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
