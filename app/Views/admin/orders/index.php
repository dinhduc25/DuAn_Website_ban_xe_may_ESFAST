<div class="admin-orders-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-shopping-cart text-primary"></i> Quản Lý Đơn Hàng Mua Xe</h1>
    </div>

    <!-- Filter and Search -->
    <div class="admin-card toolbar-card mb-4">
        <form action="/admin/orders" method="GET" class="admin-filter-form">
            <div class="filter-col search-input-col">
                <input type="text" name="q" class="form-control" placeholder="Tìm theo mã đơn (ESF-...), tên khách, số điện thoại..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="filter-col">
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả trạng thái đơn --</option>
                    <option value="pending" <?= ($status === 'pending') ? 'selected' : '' ?>>Chờ xử lý</option>
                    <option value="processing" <?= ($status === 'processing') ? 'selected' : '' ?>>Đang chuẩn bị xe</option>
                    <option value="shipping" <?= ($status === 'shipping') ? 'selected' : '' ?>>Đang vận chuyển</option>
                    <option value="completed" <?= ($status === 'completed') ? 'selected' : '' ?>>Đã hoàn tất</option>
                    <option value="cancelled" <?= ($status === 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
                </select>
            </div>
            <div class="filter-col">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Tìm kiếm</button>
                <a href="/admin/orders" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã Đơn Hàng</th>
                        <th>Khách Hàng</th>
                        <th>Điện Thoại</th>
                        <th>Địa Chỉ Nhận Xe</th>
                        <th>Tổng Tiền</th>
                        <th>Phương Thức</th>
                        <th>Thanh Toán</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Tạo</th>
                        <th class="text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $ord): ?>
                            <tr>
                                <td><strong class="text-primary">#<?= htmlspecialchars($ord['order_code']) ?></strong></td>
                                <td><?= htmlspecialchars($ord['customer_name']) ?></td>
                                <td><?= htmlspecialchars($ord['customer_phone']) ?></td>
                                <td><small class="text-muted text-truncate d-inline-block" style="max-width: 200px;"><?= htmlspecialchars($ord['shipping_address']) ?></small></td>
                                <td><strong><?= number_format($ord['total_amount'], 0, ',', '.') ?> đ</strong></td>
                                <td><span class="badge-method"><?= strtoupper($ord['payment_method']) ?></span></td>
                                <td>
                                    <span class="badge-status status-<?= $ord['payment_status'] ?>">
                                        <?= $ord['payment_status'] === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status status-<?= $ord['order_status'] ?>">
                                        <?= match($ord['order_status']) {
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang chuẩn bị',
                                            'shipping' => 'Đang vận chuyển',
                                            'completed' => 'Hoàn tất',
                                            'cancelled' => 'Đã hủy',
                                            default => $ord['order_status']
                                        } ?>
                                    </span>
                                </td>
                                <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></small></td>
                                <td class="text-center">
                                    <a href="/admin/orders/<?= $ord['id'] ?>" class="btn btn-sm btn-primary" title="Xem chi tiết & xử lý đơn">
                                        <i class="fas fa-eye"></i> Xử Lý
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">Không tìm thấy đơn hàng nào phù hợp.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="pagination-wrapper mt-4">
                <ul class="pagination-list">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li><a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>" class="page-link <?= $i === $pagination['page'] ? 'active' : '' ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
