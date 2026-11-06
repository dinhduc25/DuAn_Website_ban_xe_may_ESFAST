<div class="admin-order-detail-page">
    <div class="page-top-actions">
        <div>
            <h1 class="page-title"><i class="fas fa-file-invoice text-primary"></i> Đơn Hàng #<?= htmlspecialchars($order['order_code']) ?></h1>
            <p class="text-muted"><i class="far fa-clock"></i> Ngày tạo: <?= date('d/m/Y H:i:s', strtotime($order['created_at'])) ?></p>
        </div>
        <a href="/admin/orders" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
    </div>

    <!-- Status Transition & Actions Card -->
    <div class="admin-card action-banner-card mt-4">
        <div class="status-action-row">
            <!-- Update Order Status Form -->
            <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST" class="status-update-form">
                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                <div class="form-group-inline">
                    <label for="order_status"><strong>Trạng Thái Đơn Hàng:</strong></label>
                    <select name="order_status" id="order_status" class="form-control" style="width: auto;">
                        <option value="pending" <?= ($order['order_status'] === 'pending') ? 'selected' : '' ?>>Chờ xử lý (Pending)</option>
                        <option value="processing" <?= ($order['order_status'] === 'processing') ? 'selected' : '' ?>>Đang chuẩn bị xe (Processing)</option>
                        <option value="shipping" <?= ($order['order_status'] === 'shipping') ? 'selected' : '' ?>>Đang vận chuyển (Shipping)</option>
                        <option value="completed" <?= ($order['order_status'] === 'completed') ? 'selected' : '' ?>>Đã hoàn tất giao xe (Completed)</option>
                        <option value="cancelled" <?= ($order['order_status'] === 'cancelled') ? 'selected' : '' ?>>Hủy đơn hàng (Cancelled)</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-sync"></i> Cập Nhật</button>
                </div>
            </form>

            <!-- Update Payment Status Form -->
            <form action="/admin/orders/<?= $order['id'] ?>/payment" method="POST" class="payment-update-form">
                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                <div class="form-group-inline">
                    <label for="payment_status"><strong>Thanh Toán:</strong></label>
                    <select name="payment_status" id="payment_status" class="form-control" style="width: auto;">
                        <option value="unpaid" <?= ($order['payment_status'] === 'unpaid') ? 'selected' : '' ?>>Chưa thanh toán</option>
                        <option value="paid" <?= ($order['payment_status'] === 'paid') ? 'selected' : '' ?>>Đã thanh toán đủ</option>
                        <option value="refunded" <?= ($order['payment_status'] === 'refunded') ? 'selected' : '' ?>>Đã hoàn tiền</option>
                    </select>
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>

    <div class="order-detail-boxes-grid mt-4">
        <!-- Customer Box -->
        <div class="admin-card">
            <h3 class="card-title"><i class="fas fa-user text-primary"></i> Thông Tin Khách Hàng</h3>
            <p><strong>Họ và tên:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p><strong>Số điện thoại:</strong> <a href="tel:<?= htmlspecialchars($order['customer_phone']) ?>"><?= htmlspecialchars($order['customer_phone']) ?></a></p>
            <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($order['customer_email']) ?>"><?= htmlspecialchars($order['customer_email']) ?></a></p>
            <p><strong>Địa chỉ bàn giao:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
            <?php if (!empty($order['notes'])): ?>
                <p><strong>Ghi chú đơn:</strong> <span class="text-info"><?= htmlspecialchars($order['notes']) ?></span></p>
            <?php endif; ?>
        </div>

        <!-- Payment & System Transaction Box -->
        <div class="admin-card">
            <h3 class="card-title"><i class="fas fa-money-check-alt text-primary"></i> Thông Tin Giao Dịch</h3>
            <p><strong>Phương thức:</strong> 
                <?= match($order['payment_method']) {
                    'cod' => 'Thanh toán tiền mặt khi nhận xe (COD)',
                    'bank_transfer' => 'Chuyển khoản Ngân hàng',
                    'online_gateway' => 'Cổng thanh toán điện tử (VNPAY/MoMo)',
                    default => $order['payment_method']
                } ?>
            </p>
            <p><strong>Trạng thái thanh toán:</strong> 
                <span class="badge-status status-<?= $order['payment_status'] ?>">
                    <?= $order['payment_status'] === 'paid' ? 'ĐÃ THANH TOÁN' : 'CHƯA THANH TOÁN' ?>
                </span>
            </p>
            <?php if (!empty($order['payment'])): ?>
                <p><strong>Mã GD:</strong> <code><?= htmlspecialchars($order['payment']['transaction_code']) ?></code></p>
                <p><strong>Phản hồi cổng TT:</strong> <small class="text-muted"><?= htmlspecialchars($order['payment']['payment_response'] ?? '') ?></small></p>
            <?php endif; ?>
            <?php if (!empty($order['cancel_reason'])): ?>
                <div class="alert alert-danger mt-2">
                    <strong>Lý do hủy đơn:</strong> <?= htmlspecialchars($order['cancel_reason']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Items Table Card -->
    <div class="admin-card mt-4">
        <h3 class="card-title"><i class="fas fa-motorcycle text-primary"></i> Danh Sách Xe & Biến Thể</h3>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Dòng Xe</th>
                        <th>Phiên Bản & Màu Sắc</th>
                        <th>Đơn Giá</th>
                        <th>Số Lượng</th>
                        <th>Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['details'] as $item): ?>
                        <tr>
                            <td>
                                <div class="table-thumb">
                                    <img src="/assets/images/<?= htmlspecialchars($item['product_image'] ?? 'bike_fallback.png') ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" onerror="this.src='/assets/images/bike_fallback.png'">
                                </div>
                            </td>
                            <td><strong><?= htmlspecialchars($item['product_name']) ?></strong></td>
                            <td>
                                <?php if (!empty($item['variant_info'])): ?>
                                    <span class="badge-version"><?= htmlspecialchars($item['variant_info']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Bản tiêu chuẩn</span>
                                <?php endif; ?>
                            </td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</td>
                            <td><strong><?= $item['quantity'] ?></strong></td>
                            <td><strong class="text-primary"><?= number_format($item['total_price'], 0, ',', '.') ?> VNĐ</strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Tạm tính:</strong></td>
                        <td><?= number_format($order['subtotal'], 0, ',', '.') ?> VNĐ</td>
                    </tr>
                    <?php if ($order['discount_amount'] > 0): ?>
                        <tr class="text-success">
                            <td colspan="5" class="text-right"><strong>Giảm giá voucher:</strong></td>
                            <td>-<?= number_format($order['discount_amount'], 0, ',', '.') ?> VNĐ</td>
                        </tr>
                    <?php endif; ?>
                    <tr class="total-final-row">
                        <td colspan="5" class="text-right"><h3>TỔNG TIỀN ĐƠN HÀNG:</h3></td>
                        <td><h3 class="text-primary"><?= number_format($order['total_amount'], 0, ',', '.') ?> VNĐ</h3></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
