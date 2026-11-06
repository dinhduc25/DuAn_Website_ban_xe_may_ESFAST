/**
 * ESFAST ECOMMERCE - CLIENT JAVASCRIPT
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Menu Drawer
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');
    const drawerBackdrop = document.getElementById('drawerBackdrop');

    if (mobileMenuToggle && mobileDrawer && drawerBackdrop) {
        mobileMenuToggle.addEventListener('click', function () {
            mobileDrawer.classList.add('active');
            drawerBackdrop.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        const closeDrawer = function () {
            mobileDrawer.classList.remove('active');
            drawerBackdrop.classList.remove('active');
            document.body.style.overflow = '';
        };

        if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', closeDrawer);
        drawerBackdrop.addEventListener('click', closeDrawer);
    }

    // 2. User Account Dropdown
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
            if (!userDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });
    }

    // 3. Hero Slider Autoplay
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroDots = document.querySelectorAll('.hero-dot');
    let currentHeroIndex = 0;
    let heroInterval = null;

    if (heroSlides.length > 1) {
        window.switchHeroSlide = function (index) {
            heroSlides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            heroDots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
            currentHeroIndex = index;
        };

        const startHeroAutoplay = function () {
            heroInterval = setInterval(() => {
                let nextIdx = (currentHeroIndex + 1) % heroSlides.length;
                window.switchHeroSlide(nextIdx);
            }, 6000);
        };

        startHeroAutoplay();

        const sliderEl = document.getElementById('heroSlider');
        if (sliderEl) {
            sliderEl.addEventListener('mouseenter', () => clearInterval(heroInterval));
            sliderEl.addEventListener('mouseleave', () => startHeroAutoplay());
        }
    }

    // 4. Auto-dismiss Alert Banners
    const alerts = document.querySelectorAll('.alert-banner');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, max-height 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // 5. AJAX Quick Add-to-cart for products
    const quickForms = document.querySelectorAll('.form-add-to-cart-quick');
    quickForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalHtml = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;

                if (data.success) {
                    showToast(data.message, 'success');
                    // Update header badge
                    const badge = document.getElementById('headerCartCount');
                    if (badge) badge.innerText = data.cart_count;
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
                form.submit(); // fallback to normal submit
            });
        });
    });

    // Toast Notification Utility
    window.showToast = function (message, type = 'info') {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast-item toast-${type}`;
        const icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');

        toast.innerHTML = `
            <i class="fas ${icon}"></i>
            <span>${message}</span>
            <button type="button" class="btn-close-toast">&times;</button>
        `;

        toast.querySelector('.btn-close-toast').addEventListener('click', () => toast.remove());
        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    };
});
