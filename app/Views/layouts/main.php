<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'ESFAST - Xe Máy Điện Thông Minh Đỉnh Cao') ?></title>
    
    <!-- Meta SEO -->
    <meta name="description" content="ESFAST – Thương hiệu xe máy điện thông minh tiên phong tại Việt Nam. Pin LFP bền bỉ, di chuyển trên 200km/sạc, chuẩn chống nước IP67.">
    <meta name="keywords" content="xe máy điện, ESFAST, xe điện Vinfast, xe điện thông minh, pin LFP, xe máy điện đi xa nhất">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? 'ESFAST') ?>">
    <meta property="og:type" content="website">
    
    <!-- Google Fonts: Plus Jakarta Sans / Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 Free CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="site-body">
    <!-- Header -->
    <?php require __DIR__ . '/header.php'; ?>

    <!-- Flash Messages (Toasts) -->
    <?php if (!empty($flashSuccess)): ?>
        <div class="alert-banner alert-success" role="alert">
            <div class="container alert-inner">
                <i class="fas fa-check-circle"></i>
                <span><?= htmlspecialchars($flashSuccess) ?></span>
                <button type="button" class="btn-close-alert" onclick="this.parentElement.parentElement.remove();">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashError)): ?>
        <div class="alert-banner alert-danger" role="alert">
            <div class="container alert-inner">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= htmlspecialchars($flashError) ?></span>
                <button type="button" class="btn-close-alert" onclick="this.parentElement.parentElement.remove();">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashWarning)): ?>
        <div class="alert-banner alert-warning" role="alert">
            <div class="container alert-inner">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?= htmlspecialchars($flashWarning) ?></span>
                <button type="button" class="btn-close-alert" onclick="this.parentElement.parentElement.remove();">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <?php require __DIR__ . '/footer.php'; ?>

    <!-- Scripts -->
    <script src="/assets/js/main.js"></script>
</body>
</html>
