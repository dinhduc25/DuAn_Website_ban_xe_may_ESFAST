<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Tài Khoản - ESFAST') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body class="auth-body">
    <div class="auth-page-wrapper">
        <!-- Background Lighting / Ambient Glow -->
        <div class="glow-orb orb-1"></div>
        <div class="glow-orb orb-2"></div>

        <div class="auth-header-bar">
            <a href="/" class="auth-brand-logo">
                <div class="logo-symbol">
                    <span class="bolt-icon"><i class="fas fa-bolt"></i></span>
                </div>
                <div class="logo-text">
                    <span class="brand-name">ESFAST</span>
                    <span class="brand-slogan">ELECTRIC MOBILITY</span>
                </div>
            </a>
            <a href="/" class="back-home-link"><i class="fas fa-arrow-left"></i> Quay lại Trang chủ</a>
        </div>

        <div class="auth-container">
            <!-- Flash Message Alerts -->
            <?php if (!empty($flashSuccess)): ?>
                <div class="auth-alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($flashSuccess) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($flashError)): ?>
                <div class="auth-alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($flashError) ?></span>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>

        <div class="auth-footer-bar">
            <p>&copy; <?= date('Y') ?> ESFAST Việt Nam. Nền tảng xe điện thông minh hàng đầu.</p>
        </div>
    </div>

    <script src="/assets/js/main.js"></script>
</body>
</html>
