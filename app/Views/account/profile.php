<div class="account-page">
    <div class="breadcrumb-bar">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li class="active">Tài Khoản Cá Nhân</li>
            </ul>
        </div>
    </div>

    <div class="container account-container">
        <div class="account-layout">
            <!-- Sidebar Navigation -->
            <aside class="account-sidebar">
                <div class="account-user-card">
                    <div class="account-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h4><?= htmlspecialchars($user['full_name']) ?></h4>
                    <span class="user-role-tag"><i class="fas fa-shield-alt"></i> Khách Hàng Thân Thiết</span>
                </div>

                <ul class="account-nav-list">
                    <li><a href="/account" class="active"><i class="fas fa-user-edit"></i> Thông Tin Cá Nhân</a></li>
                    <li><a href="/account/orders"><i class="fas fa-box"></i> Lịch Sử Mua Hàng</a></li>
                    <li><a href="/vouchers"><i class="fas fa-ticket-alt"></i> Kho Voucher Của Tôi</a></li>
                    <li><a href="/logout" class="text-danger"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a></li>
                </ul>
            </aside>

            <!-- Main Content Form -->
            <main class="account-main">
                <div class="account-card">
                    <h2 class="card-title"><i class="fas fa-id-card text-primary"></i> Cập Nhật Thông Tin Cá Nhân</h2>
                    <form action="/account/profile" method="POST">
                        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

                        <div class="form-row two-cols">
                            <div class="form-group">
                                <label class="form-label" for="full_name">Họ và Tên</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" 
                                       value="<?= htmlspecialchars($user['full_name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="username">Tên Đăng Nhập (Cố định)</label>
                                <input type="text" id="username" class="form-control bg-light" 
                                       value="<?= htmlspecialchars($user['username']) ?>" readonly>
                            </div>
                        </div>

                        <div class="form-row two-cols">
                            <div class="form-group">
                                <label class="form-label" for="email">Địa Chỉ Email</label>
                                <input type="email" name="email" id="email" class="form-control" 
                                       value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="phone">Số Điện Thoại</label>
                                <input type="tel" name="phone" id="phone" class="form-control" 
                                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="address">Địa Chỉ Thường Trú / Nhận Xe</label>
                            <textarea name="address" id="address" rows="2" class="form-control"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu Thông Tin</button>
                    </form>
                </div>

                <!-- Change Password Form -->
                <div class="account-card mt-4">
                    <h2 class="card-title"><i class="fas fa-lock text-primary"></i> Đổi Mật Khẩu Đăng Nhập</h2>
                    <form action="/account/password" method="POST">
                        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

                        <div class="form-row three-cols">
                            <div class="form-group">
                                <label class="form-label" for="current_password">Mật Khẩu Hiện Tại</label>
                                <input type="password" name="current_password" id="current_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="new_password">Mật Khẩu Mới</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="confirm_password">Nhập Lại Mật Khẩu Mới</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-primary"><i class="fas fa-key"></i> Đổi Mật Khẩu</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>
