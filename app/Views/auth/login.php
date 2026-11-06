<div class="auth-card">
    <div class="auth-card-header text-center">
        <h2 class="auth-title">Đăng Nhập Tài Khoản</h2>
        <p class="auth-sub">Chào mừng bạn quay trở lại với hệ sinh thái xe máy điện thông minh ESFAST.</p>
    </div>

    <!-- Demo Accounts Quick-Fill Section -->
    <div class="demo-accounts-box">
        <div class="demo-box-header">
            <i class="fas fa-key text-primary"></i>
            <span>Tài Khoản Thử Nghiệm Nhanh (Demo Accounts)</span>
        </div>
        <div class="demo-buttons-grid">
            <button type="button" class="btn-demo-fill demo-customer" onclick="fillDemoAccount('customer@esfast.vn', 'Customer@123')">
                <div class="demo-btn-title"><i class="fas fa-user"></i> Khách Hàng Demo</div>
                <div class="demo-btn-creds">customer@esfast.vn / Customer@123</div>
                <span class="btn-fill-badge">Điền Nhanh</span>
            </button>
            <button type="button" class="btn-demo-fill demo-admin" onclick="fillDemoAccount('admin@esfast.vn', 'Admin@123')">
                <div class="demo-btn-title"><i class="fas fa-shield-alt"></i> Quản Trị Viên Demo</div>
                <div class="demo-btn-creds">admin@esfast.vn / Admin@123</div>
                <span class="btn-fill-badge">Điền Nhanh</span>
            </button>
        </div>
    </div>

    <!-- Login Form -->
    <form action="/login" method="POST" class="auth-form" id="loginForm">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

        <div class="form-group">
            <label for="credential" class="form-label">Tên Đăng Nhập hoặc Địa Chỉ Email</label>
            <div class="input-icon-wrap">
                <i class="fas fa-envelope input-icon"></i>
                <input type="text" name="credential" id="credential" class="form-control" 
                       placeholder="Nhập username hoặc email..." 
                       value="<?= htmlspecialchars($old['credential'] ?? '') ?>" required autofocus>
            </div>
            <?php if (!empty($errors['credential'])): ?>
                <span class="field-error"><?= htmlspecialchars($errors['credential']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <div class="form-label-flex">
                <label for="password" class="form-label">Mật Khẩu</label>
            </div>
            <div class="input-icon-wrap password-wrap">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" id="password" class="form-control" 
                       placeholder="Nhập mật khẩu..." required>
                <button type="button" class="btn-toggle-pw" onclick="togglePasswordVisibility('password', this)" aria-label="Hiện/ẩn mật khẩu">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <?php if (!empty($errors['password'])): ?>
                <span class="field-error"><?= htmlspecialchars($errors['password']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-options-row">
            <label class="custom-checkbox">
                <input type="checkbox" name="remember" value="1">
                <span>Ghi nhớ đăng nhập</span>
            </label>
            <a href="/contact" class="forgot-link">Quên mật khẩu?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-auth-submit">
            <i class="fas fa-sign-in-alt"></i> Đăng Nhập Ngay
        </button>

        <div class="auth-card-footer text-center">
            <p>Chưa có tài khoản ESFAST? <a href="/register" class="text-primary font-weight-bold">Đăng ký thành viên mới</a></p>
        </div>
    </form>
</div>

<script>
function fillDemoAccount(username, password) {
    const credInput = document.getElementById('credential');
    const pwInput = document.getElementById('password');
    credInput.value = username;
    pwInput.value = password;
    
    // Highlight inputs
    credInput.classList.add('flash-input');
    pwInput.classList.add('flash-input');
    setTimeout(() => {
        credInput.classList.remove('flash-input');
        pwInput.classList.remove('flash-input');
    }, 800);
}

function togglePasswordVisibility(fieldId, btnEl) {
    const input = document.getElementById(fieldId);
    const icon = btnEl.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
