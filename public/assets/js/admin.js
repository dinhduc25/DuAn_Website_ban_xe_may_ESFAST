/**
 * ESFAST ADMIN CONTROL PANEL JAVASCRIPT
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Toggle
    const sidebar = document.getElementById('adminSidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');
    const backdrop = document.getElementById('adminSidebarBackdrop');

    if (toggleBtn && sidebar && backdrop) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.add('active');
            backdrop.classList.add('active');
        });

        const closeSidebar = function () {
            sidebar.classList.remove('active');
            backdrop.classList.remove('active');
        };

        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        backdrop.addEventListener('click', closeSidebar);
    }

    // 2. Auto-hide alerts
    const alerts = document.querySelectorAll('.alert-banner');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    });
});
