<?php
use App\Core\Auth;
use App\Models\Cart;
use App\Models\Category;

$cartCount = Cart::count();
$user = Auth::user();
$categoryModel = new Category();
$navCategories = $categoryModel->allActive();
$currentUri = $_SERVER['REQUEST_URI'] ?? '/';
?>
<header class="site-header" id="siteHeader">
    <!-- Top Announcement Bar -->
    <div class="top-bar">
        <div class="container top-bar-inner">
            <div class="top-bar-left">
                <span class="badge-tech"><i class="fas fa-bolt"></i> ESFAST 2026</span>
                <span>Kỷ nguyên di chuyển xanh – Miễn phí sạc pin 1 năm cho tất cả dòng xe</span>
            </div>
            <div class="top-bar-right">
                <a href="tel:1900888999" class="top-contact"><i class="fas fa-phone-alt"></i> Hotline: <strong>1900 888 999</strong></a>
                <span class="separator">|</span>
                <a href="/contact" class="top-contact"><i class="fas fa-map-marker-alt"></i> Hệ thống Showroom</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="main-nav">
        <div class="container nav-wrapper">
            <!-- Brand Logo -->
            <a href="/" class="brand-logo">
                <div class="logo-symbol">
                    <span class="bolt-icon"><i class="fas fa-bolt"></i></span>
                </div>
                <div class="logo-text">
                    <span class="brand-name">ESFAST</span>
                    <span class="brand-slogan">ELECTRIC MOBILITY</span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <ul class="nav-menu">
                <li class="nav-item"><a href="/" class="nav-link <?= empty($currentUri) || $currentUri === '/' || str_ends_with($currentUri, '/DuAN/') ? 'active' : '' ?>">Trang Chủ</a></li>
                <li class="nav-item dropdown">
                    <a href="/products" class="nav-link <?= str_contains($currentUri, '/products') ? 'active' : '' ?>">
                        Xe Máy Điện <i class="fas fa-chevron-down nav-arrow"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/products" class="dropdown-item"><strong>Tất Cả Dòng Xe</strong></a></li>
                        <li class="dropdown-divider"></li>
                        <?php foreach ($navCategories as $navCat): ?>
                            <li><a href="/categories/<?= htmlspecialchars($navCat['slug']) ?>" class="dropdown-item"><?= htmlspecialchars($navCat['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item"><a href="/vouchers" class="nav-link <?= str_contains($currentUri, '/vouchers') ? 'active' : '' ?>">Khuyến Mãi <span class="nav-badge">Hot</span></a></li>
                <li class="nav-item"><a href="/about" class="nav-link <?= str_contains($currentUri, '/about') ? 'active' : '' ?>">Về ESFAST</a></li>
                <li class="nav-item"><a href="/contact" class="nav-link <?= str_contains($currentUri, '/contact') ? 'active' : '' ?>">Liên Hệ</a></li>
            </ul>

            <!-- Nav Actions (Search, Cart, User) -->
            <div class="nav-actions">
                <!-- Search Button -->
                <div class="search-box">
                    <form action="/products" method="GET" class="search-form">
                        <input type="text" name="q" placeholder="Tìm kiếm dòng xe..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        <button type="submit" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <!-- Cart Button -->
                <a href="/cart" class="btn-action-icon cart-trigger" title="Giỏ hàng">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-badge" id="headerCartCount"><?= $cartCount ?></span>
                </a>

                <!-- User Account -->
                <?php if ($user): ?>
                    <div class="user-dropdown-wrapper">
                        <button class="btn-user-trigger" id="userMenuBtn">
                            <span class="user-avatar"><i class="fas fa-user-circle"></i></span>
                            <span class="user-greeting"><?= htmlspecialchars(explode(' ', $user['full_name'])[0]) ?></span>
                            <i class="fas fa-chevron-down arrow-down"></i>
                        </button>
                        <ul class="user-dropdown" id="userDropdown">
                            <li class="user-info-header">
                                <strong><?= htmlspecialchars($user['full_name']) ?></strong>
                                <small><?= htmlspecialchars($user['email']) ?></small>
                            </li>
                            <li class="divider"></li>
                            <?php if (Auth::isAdmin()): ?>
                                <li><a href="/admin" class="dropdown-link text-primary"><i class="fas fa-tachometer-alt"></i> Trang Quản Trị Admin</a></li>
                                <li class="divider"></li>
                            <?php endif; ?>
                            <li><a href="/account" class="dropdown-link"><i class="fas fa-id-card"></i> Thông Tin Cá Nhân</a></li>
                            <li><a href="/account/orders" class="dropdown-link"><i class="fas fa-file-invoice-dollar"></i> Lịch Sử Mua Hàng</a></li>
                            <li class="divider"></li>
                            <li><a href="/logout" class="dropdown-link text-danger"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="auth-buttons">
                        <a href="/login" class="btn btn-outline-primary btn-sm">Đăng Nhập</a>
                        <a href="/register" class="btn btn-primary btn-sm">Đăng Ký</a>
                    </div>
                <?php endif; ?>

                <!-- Mobile Hamburger Toggle -->
                <button class="mobile-toggle" id="mobileMenuToggle" aria-label="Mở Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Drawer Navigation -->
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="drawer-header">
            <span class="drawer-title">MENU ESFAST</span>
            <button class="btn-close-drawer" id="closeDrawerBtn"><i class="fas fa-times"></i></button>
        </div>
        <div class="drawer-body">
            <form action="/products" method="GET" class="mobile-search">
                <input type="text" name="q" placeholder="Tìm kiếm dòng xe...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <ul class="mobile-menu-links">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><a href="/products"><i class="fas fa-motorcycle"></i> Tất Cả Xe Máy Điện</a></li>
                <?php foreach ($navCategories as $navCat): ?>
                    <li class="sub-link"><a href="/categories/<?= htmlspecialchars($navCat['slug']) ?>">- <?= htmlspecialchars($navCat['name']) ?></a></li>
                <?php endforeach; ?>
                <li><a href="/vouchers"><i class="fas fa-tags"></i> Khuyến Mãi & Voucher</a></li>
                <li><a href="/about"><i class="fas fa-info-circle"></i> Về ESFAST</a></li>
                <li><a href="/contact"><i class="fas fa-envelope"></i> Liên Hệ</a></li>
                <?php if ($user): ?>
                    <li class="drawer-divider"></li>
                    <?php if (Auth::isAdmin()): ?>
                        <li><a href="/admin" class="text-primary"><i class="fas fa-cog"></i> Trang Quản Trị</a></li>
                    <?php endif; ?>
                    <li><a href="/account"><i class="fas fa-user"></i> Tài Khoản Cá Nhân</a></li>
                    <li><a href="/account/orders"><i class="fas fa-box"></i> Đơn Hàng Của Tôi</a></li>
                    <li><a href="/logout" class="text-danger"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a></li>
                <?php else: ?>
                    <li class="drawer-divider"></li>
                    <li><a href="/login"><i class="fas fa-sign-in-alt"></i> Đăng Nhập</a></li>
                    <li><a href="/register"><i class="fas fa-user-plus"></i> Đăng Ký Tài Khoản</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="drawer-backdrop" id="drawerBackdrop"></div>
</header>
