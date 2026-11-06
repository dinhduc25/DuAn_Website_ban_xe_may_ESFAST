<div class="admin-users-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-users text-primary"></i> Quản Lý Tài Khoản Khách Hàng</h1>
    </div>

    <!-- Search Toolbar -->
    <div class="admin-card toolbar-card mb-4">
        <form action="/admin/users" method="GET" class="admin-filter-form">
            <div class="filter-col search-input-col">
                <input type="text" name="q" class="form-control" placeholder="Tìm theo tên, email, số điện thoại, username..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="filter-col">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Tìm kiếm</button>
                <a href="/admin/users" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ và Tên</th>
                        <th>Tên Đăng Nhập</th>
                        <th>Email</th>
                        <th>Số Điện Thoại</th>
                        <th>Đơn Hoàn Tất</th>
                        <th>Tổng Chi Tiêu</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Tham Gia</th>
                        <th class="text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>#<?= $u['id'] ?></td>
                                <td><strong><?= htmlspecialchars($u['full_name']) ?></strong></td>
                                <td><code><?= htmlspecialchars($u['username']) ?></code></td>
                                <td><a href="mailto:<?= htmlspecialchars($u['email']) ?>"><?= htmlspecialchars($u['email']) ?></a></td>
                                <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
                                <td>
                                    <span class="badge-count"><?= $u['order_count'] ?? 0 ?> đơn</span>
                                </td>
                                <td>
                                    <strong class="text-success"><?= number_format($u['total_spent'] ?? 0, 0, ',', '.') ?> đ</strong>
                                </td>
                                <td>
                                    <span class="badge-status status-<?= $u['status'] ?>">
                                        <?= $u['status'] === 'active' ? 'Hoạt động' : 'Đã khóa' ?>
                                    </span>
                                </td>
                                <td><small class="text-muted"><?= date('d/m/Y', strtotime($u['created_at'])) ?></small></td>
                                <td class="text-center">
                                    <form action="/admin/users/toggle/<?= $u['id'] ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái tài khoản này?');">
                                        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                        <?php if ($u['status'] === 'active'): ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Khóa tài khoản">
                                                <i class="fas fa-lock"></i> Khóa
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Mở khóa tài khoản">
                                                <i class="fas fa-unlock"></i> Mở khóa
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">Không tìm thấy khách hàng nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="pagination-wrapper mt-4">
                <ul class="pagination-list">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li><a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>" class="page-link <?= $i === $pagination['page'] ? 'active' : '' ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
