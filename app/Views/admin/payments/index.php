<div class="admin-payments-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-credit-card text-primary"></i> Lịch Sử Giao Dịch & Thanh Toán</h1>
    </div>

    <!-- Filter by Status -->
    <div class="admin-card toolbar-card mb-4">
        <form action="/admin/payments" method="GET" class="admin-filter-form">
            <div class="filter-col">
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả trạng thái giao dịch --</option>
                    <option value="pending" <?= ($status === 'pending') ? 'selected' : '' ?>>Đang chờ thanh toán (Pending)</option>
                    <option value="success" <?= ($status === 'success') ? 'selected' : '' ?>>Thành công (Success)</option>
                    <option value="failed" <?= ($status === 'failed') ? 'selected' : '' ?>>Thất bại (Failed)</option>
                </select>
            </div>
            <div class="filter-col">
                <a href="/admin/payments" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã Giao Dịch</th>
                        <th>Mã Đơn Hàng</th>
                        <th>Khách Hàng</th>
                        <th>Số Tiền</th>
                        <th>Phương Thức</th>
                        <th>Phản Hồi / Chi Tiết</th>
                        <th>Trạng Thái</th>
                        <th>Thời Gian</th>
                        <th class="text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($payments)): ?>
                        <?php foreach ($payments as $pay): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($pay['transaction_code']) ?></code></td>
                                <td>
                                    <a href="/admin/orders/<?= $pay['order_id'] ?>" class="text-primary font-weight-bold">
                                        #<?= htmlspecialchars($pay['order_code']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($pay['customer_name']) ?></td>
                                <td><strong><?= number_format($pay['amount'], 0, ',', '.') ?> VNĐ</strong></td>
                                <td><span class="badge-method"><?= strtoupper($pay['payment_method']) ?></span></td>
                                <td><small class="text-muted text-truncate d-inline-block" style="max-width: 250px;"><?= htmlspecialchars($pay['payment_response'] ?? '') ?></small></td>
                                <td>
                                    <span class="badge-status status-<?= $pay['status'] === 'success' ? 'paid' : ($pay['status'] === 'failed' ? 'cancelled' : 'pending') ?>">
                                        <?= match($pay['status']) {
                                            'success' => 'Thành công',
                                            'pending' => 'Chờ thanh toán',
                                            'failed' => 'Thất bại',
                                            default => $pay['status']
                                        } ?>
                                    </span>
                                </td>
                                <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($pay['created_at'])) ?></small></td>
                                <td class="text-center">
                                    <?php if ($pay['status'] === 'pending'): ?>
                                        <form action="/admin/payments/confirm/<?= $pay['id'] ?>" method="POST" onsubmit="return confirm('Xác nhận đã nhận đủ tiền thanh toán cho giao dịch này?');">
                                            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                            <button type="submit" class="btn btn-sm btn-success" title="Xác nhận thanh toán thành công">
                                                <i class="fas fa-check"></i> Xác nhận
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted"><i class="fas fa-check-double text-success"></i> Đã duyệt</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Chưa có giao dịch thanh toán nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="pagination-wrapper mt-4">
                <ul class="pagination-list">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li><a href="?page=<?= $i ?>&status=<?= urlencode($status) ?>" class="page-link <?= $i === $pagination['page'] ? 'active' : '' ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
