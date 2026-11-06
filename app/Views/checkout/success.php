<div class="order-success-page">
    <div class="container">
        <div class="success-card">
            <div class="success-icon-wrap">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="success-title">ĐẶT HÀNG THÀNH CÔNG!</h1>
            <p class="success-subtitle">Cảm ơn Quý khách <strong><?= htmlspecialchars($order['customer_name']) ?></strong> đã tin tưởng lựa chọn xe máy điện thông minh ESFAST.</p>

            <!-- Order Code High-Tech Pill -->
            <div class="order-code-box">
                <span class="lbl">MÃ ĐƠN HÀNG:</span>
                <span class="val"><?= htmlspecialchars($order['order_code']) ?></span>
            </div>

            <div class="order-meta-grid">
                <div class="meta-item">
                    <span class="meta-lbl"><i class="far fa-calendar-alt"></i> Thời gian đặt:</span>
                    <span class="meta-val"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-lbl"><i class="fas fa-credit-card"></i> Phương thức thanh toán:</span>
                    <span class="meta-val">
                        <?= match($order['payment_method']) {
                            'cod' => 'Tiền mặt khi nhận xe (COD)',
                            'bank_transfer' => 'Chuyển khoản Ngân hàng',
                            'online_gateway' => 'Cổng thanh toán điện tử (VNPAY/MoMo)',
                            default => $order['payment_method']
                        } ?>
                    </span>
                </div>
                <div class="meta-item">
                    <span class="meta-lbl"><i class="fas fa-info-circle"></i> Trạng thái thanh toán:</span>
                    <span class="badge-status status-<?= $order['payment_status'] ?>">
                        <?= match($order['payment_status']) {
                            'paid' => 'Đã thanh toán',
                            'unpaid' => 'Chưa thanh toán (Thanh toán khi nhận hàng)',
                            'refunded' => 'Đã hoàn tiền',
                            default => $order['payment_status']
                        } ?>
                    </span>
                </div>
                <div class="meta-item">
                    <span class="meta-lbl"><i class="fas fa-shipping-fast"></i> Trạng thái đơn:</span>
                    <span class="badge-status status-<?= $order['order_status'] ?>">
                        <?= match($order['order_status']) {
                            'pending' => 'Đang chờ xử lý',
                            'processing' => 'Đang chuẩn bị xe & hồ sơ',
                            'shipping' => 'Đang vận chuyển bàn giao',
                            'completed' => 'Đã hoàn tất',
                            'cancelled' => 'Đã hủy',
                            default => $order['order_status']
                        } ?>
                    </span>
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="order-delivery-info">
                <h3><i class="fas fa-map-marker-alt text-primary"></i> Thông Tin Nhận Bàn Giao Xe</h3>
                <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['customer_name']) ?> (SĐT: <?= htmlspecialchars($order['customer_phone']) ?>)</p>
                <p><strong>Email xác nhận:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                <p><strong>Địa chỉ bàn giao:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
                <?php if (!empty($order['notes'])): ?>
                    <p><strong>Ghi chú:</strong> <?= htmlspecialchars($order['notes']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Items List -->
            <div class="order-items-table-wrap">
                <h3><i class="fas fa-motorcycle text-primary"></i> Danh Sách Xe Trong Đơn Hàng</h3>
                <table class="table-order-items">
                    <thead>
                        <tr>
                            <th>Xe Máy Điện</th>
                            <th>Đơn Giá</th>
                            <th>Số Lượng</th>
                            <th>Tổng Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['details'] as $item): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                    <?php if (!empty($item['variant_info'])): ?>
                                        <div class="text-muted"><small><?= htmlspecialchars($item['variant_info']) ?></small></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</td>
                                <td><?= $item['quantity'] ?></td>
                                <td><strong><?= number_format($item['total_price'], 0, ',', '.') ?> VNĐ</strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">Tạm tính:</td>
                            <td><?= number_format($order['subtotal'], 0, ',', '.') ?> VNĐ</td>
                        </tr>
                        <?php if ($order['discount_amount'] > 0): ?>
                            <tr class="text-success">
                                <td colspan="3" class="text-right">Giảm giá voucher:</td>
                                <td>-<?= number_format($order['discount_amount'], 0, ',', '.') ?> VNĐ</td>
                            </tr>
                        <?php endif; ?>
                        <tr class="total-final-row">
                            <td colspan="3" class="text-right"><strong>TỔNG TIỀN THANH TOÁN:</strong></td>
                            <td><strong class="text-primary text-xl"><?= number_format($order['total_amount'], 0, ',', '.') ?> VNĐ</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="success-actions">
                <a href="/" class="btn btn-outline-primary"><i class="fas fa-home"></i> Về Trang Chủ</a>
                <?php if (\App\Core\Auth::check()): ?>
                    <a href="/account/orders" class="btn btn-primary"><i class="fas fa-box"></i> Xem Đơn Hàng Của Tôi</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
