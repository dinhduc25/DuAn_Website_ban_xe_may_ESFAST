<div class="admin-admins-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-user-shield text-primary"></i> Quản Trị Viên Hệ Thống</h1>
    </div>

    <div class="category-layout-grid mt-4">
        <!-- Admins List -->
        <div class="category-list-col">
            <div class="admin-card">
                <h3 class="card-title">Danh Sách Quản Trị Viên (<?= count($admins) ?>)</h3>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Họ và Tên</th>
                                <th>Tên Đăng Nhập</th>
                                <th>Email</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $ad): ?>
                                <tr>
                                    <td>#<?= $ad['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($ad['full_name']) ?></strong></td>
                                    <td><code><?= htmlspecialchars($ad['username']) ?></code></td>
                                    <td><?= htmlspecialchars($ad['email']) ?></td>
                                    <td>
                                        <span class="badge-status status-<?= $ad['status'] ?>">
                                            <?= $ad['status'] === 'active' ? 'Hoạt động' : 'Đã khóa' ?>
                                        </span>
                                    </td>
                                    <td><small class="text-muted"><?= date('d/m/Y', strtotime($ad['created_at'])) ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Admin Form -->
        <div class="category-form-col">
            <div class="admin-card">
                <h3 class="card-title"><i class="fas fa-plus-circle text-primary"></i> Thêm Tài Khoản Quản Trị</h3>
                <form action="/admin/users/admins" method="POST">
                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

                    <div class="form-group">
                        <label class="form-label" for="full_name">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Nguyễn Quản Trị" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="username">Tên Đăng Nhập <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="admin_mod" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Địa Chỉ Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="admin@esfast.vn" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Mật Khẩu Khởi Tạo <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-3">
                        <i class="fas fa-plus"></i> Tạo Tài Khoản Quản Trị
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
