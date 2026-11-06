<?php
use App\Core\Auth;
$adminUser = Auth::user();
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Quản Trị Hệ Thống - ESFAST') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="/admin" class="sidebar-brand">
                    <span class="bolt-icon"><i class="fas fa-bolt"></i></span>
                    <span class="brand-text">ESFAST <small>ADMIN</small></span>
                </a>
                <button class="btn-sidebar-close" id="sidebarCloseBtn"><i class="fas fa-times"></i></button>
            </div>

            <div class="sidebar-user">
                <div class="avatar-circle">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="user-meta">
                    <div class="user-name"><?= htmlspecialchars($adminUser['full_name'] ?? 'Admin') ?></div>
                    <div class="user-role-badge">Super Admin</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-label">TỔNG QUAN</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/admin/dashboard" class="menu-link <?= str_contains($currentUri, '/admin/dashboard') || $currentUri === '/admin' ? 'active' : '' ?>">
                            <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                        </a>
                    </li>
                </ul>

                <div class="nav-section-label">BÁN HÀNG & SẢN PHẨM</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/admin/products" class="menu-link <?= str_contains($currentUri, '/admin/products') ? 'active' : '' ?>">
                            <i class="fas fa-motorcycle"></i> <span>Xe Máy Điện & Biến Thể</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/categories" class="menu-link <?= str_contains($currentUri, '/admin/categories') ? 'active' : '' ?>">
                            <i class="fas fa-th-large"></i> <span>Danh Mục Xe</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/orders" class="menu-link <?= str_contains($currentUri, '/admin/orders') ? 'active' : '' ?>">
                            <i class="fas fa-shopping-cart"></i> <span>Đơn Hàng</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/vouchers" class="menu-link <?= str_contains($currentUri, '/admin/vouchers') ? 'active' : '' ?>">
                            <i class="fas fa-ticket-alt"></i> <span>Mã Giảm Giá / Voucher</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/payments" class="menu-link <?= str_contains($currentUri, '/admin/payments') ? 'active' : '' ?>">
                            <i class="fas fa-credit-card"></i> <span>Giao Dịch Thanh Toán</span>
                        </a>
                    </li>
                </ul>

                <div class="nav-section-label">NGƯỜI DÙNG & NỘI DUNG</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/admin/users" class="menu-link <?= str_contains($currentUri, '/admin/users') && !str_contains($currentUri, '/admins') ? 'active' : '' ?>">
                            <i class="fas fa-users"></i> <span>Khách Hàng</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/users/admins" class="menu-link <?= str_contains($currentUri, '/admin/users/admins') ? 'active' : '' ?>">
                            <i class="fas fa-user-shield"></i> <span>Tài Khoản Quản Trị</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/content/banners" class="menu-link <?= str_contains($currentUri, '/admin/content/banners') ? 'active' : '' ?>">
                            <i class="fas fa-images"></i> <span>Banner Trang Chủ</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/content/posts" class="menu-link <?= str_contains($currentUri, '/admin/content/posts') ? 'active' : '' ?>">
                            <i class="fas fa-newspaper"></i> <span>Tin Tức & Bài Viết</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/content/contacts" class="menu-link <?= str_contains($currentUri, '/admin/content/contacts') ? 'active' : '' ?>">
                            <i class="fas fa-headset"></i> <span>Yêu Cầu Hỗ Trợ</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/reports" class="menu-link <?= str_contains($currentUri, '/admin/reports') ? 'active' : '' ?>">
                            <i class="fas fa-chart-line"></i> <span>Báo Cáo & Thống Kê</span>
                        </a>
                    </li>
                </ul>

                <div class="nav-section-label">HỆ THỐNG</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/" target="_blank" class="menu-link">
                            <i class="fas fa-external-link-alt"></i> <span>Xem Trang Khách Hàng</span>
                        </a>
                    </li>
                    <li>
                        <a href="/logout" class="menu-link text-danger">
                            <i class="fas fa-sign-out-alt"></i> <span>Đăng Xuất</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>

        <!-- Admin Main Content Wrapper -->
        <div class="admin-content-wrapper">
            <!-- Top Admin Header -->
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="btn-toggle-sidebar" id="sidebarToggleBtn" aria-label="Mở Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Admin ESFAST') ?></h2>
                </div>
                <div class="topbar-right">
                    <a href="/admin/products/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Thêm Xe Mới
                    </a>
                    <div class="admin-profile-pill">
                        <i class="fas fa-user-circle"></i>
                        <span><?= htmlspecialchars($adminUser['full_name'] ?? 'Admin') ?></span>
                    </div>
                </div>
            </header>

            <!-- Alerts -->
            <div class="admin-alert-area">
                <?php if (!empty($flashSuccess)): ?>
                    <div class="alert-banner alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?= htmlspecialchars($flashSuccess) ?></span>
                        <button type="button" class="btn-close-alert" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($flashError)): ?>
                    <div class="alert-banner alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?= htmlspecialchars($flashError) ?></span>
                        <button type="button" class="btn-close-alert" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Page Body -->
            <main class="admin-main">
                <?= $content ?>
            </main>

            <!-- Admin Footer -->
            <footer class="admin-footer">
                <p>&copy; <?= date('Y') ?> ESFAST ECOMMERCE ADMIN CONTROL PANEL. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script src="/assets/js/admin.js"></script>
</body>
</html>
