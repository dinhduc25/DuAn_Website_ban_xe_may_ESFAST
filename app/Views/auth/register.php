<div class="auth-card register-card">
    <div class="auth-card-header text-center">
        <h2 class="auth-title">Đăng Ký Tài Khoản ESFAST</h2>
        <p class="auth-sub">Tạo tài khoản để quản lý đơn hàng, theo dõi xe và nhận các voucher độc quyền.</p>
    </div>

    <form action="/register" method="POST" class="auth-form" id="registerForm">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

        <div class="form-row two-cols">
            <div class="form-group">
                <label for="full_name" class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                <div class="input-icon-wrap">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="full_name" id="full_name" class="form-control" 
                           placeholder="Ví dụ: Nguyễn Văn An" 
                           value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" required>
                </div>
                <?php if (!empty($errors['full_name'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['full_name']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Tên Đăng Nhập <span class="text-danger">*</span></label>
                <div class="input-icon-wrap">
                    <i class="fas fa-at input-icon"></i>
                    <input type="text" name="username" id="username" class="form-control" 
                           placeholder="username123" 
                           value="<?= htmlspecialchars($old['username'] ?? '') ?>" required>
                </div>
                <?php if (!empty($errors['username'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['username']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-row two-cols">
            <div class="form-group">
                <label for="email" class="form-label">Địa Chỉ Email <span class="text-danger">*</span></label>
                <div class="input-icon-wrap">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" id="email" class="form-control" 
                           placeholder="email@example.com" 
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                </div>
                <?php if (!empty($errors['email'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['email']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Số Điện Thoại <span class="text-danger">*</span></label>
                <div class="input-icon-wrap">
                    <i class="fas fa-phone input-icon"></i>
                    <input type="tel" name="phone" id="phone" class="form-control" 
                           placeholder="0912345678" 
                           value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                </div>
                <?php if (!empty($errors['phone'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['phone']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-row two-cols">
            <div class="form-group">
                <label for="reg_password" class="form-label">Mật Khẩu <span class="text-danger">*</span></label>
                <div class="input-icon-wrap password-wrap">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="reg_password" class="form-control" 
                           placeholder="Tối thiểu 6 ký tự" required>
                    <button type="button" class="btn-toggle-pw" onclick="togglePasswordVisibility('reg_password', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <?php if (!empty($errors['password'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['password']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password_confirm" class="form-label">Xác Nhận Mật Khẩu <span class="text-danger">*</span></label>
                <div class="input-icon-wrap password-wrap">
                    <i class="fas fa-shield-alt input-icon"></i>
                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" 
                           placeholder="Nhập lại mật khẩu" required>
                    <button type="button" class="btn-toggle-pw" onclick="togglePasswordVisibility('password_confirm', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <?php if (!empty($errors['password_confirm'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['password_confirm']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Địa Chỉ Thường Trú</label>
            <div class="input-icon-wrap">
                <i class="fas fa-map-marker-alt input-icon"></i>
                <input type="text" name="address" id="address" class="form-control" 
                       placeholder="Số nhà, đường, quận/huyện, tỉnh thành..." 
                       value="<?= htmlspecialchars($old['address'] ?? '') ?>">
            </div>
        </div>

        <div class="form-options-row">
            <label class="custom-checkbox">
                <input type="checkbox" name="agree_terms" value="1" required>
                <span>Tôi đồng ý với <a href="/about" target="_blank" class="text-primary">Điều khoản dịch vụ</a> và <a href="/about" target="_blank" class="text-primary">Chính sách bảo mật</a> của ESFAST.</span>
            </label>
        </div>
        <?php if (!empty($errors['agree_terms'])): ?>
            <span class="field-error"><?= htmlspecialchars($errors['agree_terms']) ?></span>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary btn-block btn-auth-submit mt-3">
            <i class="fas fa-user-plus"></i> Hoàn Tất Đăng Ký
        </button>

        <div class="auth-card-footer text-center">
            <p>Đã có tài khoản thành viên? <a href="/login" class="text-primary font-weight-bold">Đăng nhập tại đây</a></p>
        </div>
    </form>
</div>

<script>
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
