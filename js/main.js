/**
 * ESFAST ECOMMERCE - MAIN JAVASCRIPT (VANILLA JS)
 * Common components: Header, Navigation, Drawer, Search, Alerts
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Drawer Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuToggle');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');
    const drawerBackdrop = document.getElementById('drawerBackdrop');

    if (mobileMenuBtn && mobileDrawer) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileDrawer.classList.add('active');
            if (drawerBackdrop) drawerBackdrop.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        const closeDrawer = () => {
            mobileDrawer.classList.remove('active');
            if (drawerBackdrop) drawerBackdrop.classList.remove('active');
            document.body.style.overflow = '';
        };

        if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', closeDrawer);
        if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeDrawer);
    }

    // 2. Global Search Handler
    const searchForms = document.querySelectorAll('.header-search-form');
    searchForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const input = form.querySelector('input[name="search"]');
            if (input && input.value.trim()) {
                window.location.href = `products.html?search=${encodeURIComponent(input.value.trim())}`;
            }
        });
    });

    // 3. Highlight Current Nav Link
    const currentPath = window.location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('.nav-link, .drawer-nav-link');
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPath || (currentPath === '' && href === 'index.html')) {
            link.classList.add('active');
        }
    });
});
