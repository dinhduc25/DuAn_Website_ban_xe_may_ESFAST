<div class="account-page">
    <div class="breadcrumb-bar">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li><a href="/account">Tài Khoản</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li><a href="/account/orders">Đơn Hàng</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li class="active">#<?= htmlspecialchars($order['order_code']) ?></li>
            </ul>
        </div>
    </div>

    <div class="container account-container">
        <div class="account-card">
            <div class="card-header-flex">
                <div>
                    <h2 class="card-title">Chi Tiết Đơn Hàng #<?= htmlspecialchars($order['order_code']) ?></h2>
                    <span class="text-muted"><i class="far fa-clock"></i> Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                </div>
                <div>
                    <span class="badge-status status-<?= $order['order_status'] ?>">
                        Trạng thái: 
                        <?= match($order['order_status']) {
                            'pending' => 'Chờ xử lý',
                            'processing' => 'Đang chuẩn bị xe',
                            'shipping' => 'Đang vận chuyển',
                            'completed' => 'Đã giao thành công',
                            'cancelled' => 'Đã hủy',
                            default => $order['order_status']
                        } ?>
                    </span>
                </div>
            </div>

            <!-- Order info cards -->
            <div class="order-detail-boxes-grid mt-4">
                <div class="info-box">
                    <h4><i class="fas fa-map-marker-alt text-primary"></i> Địa Chỉ Bàn Giao Xe</h4>
                    <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
                    <?php if (!empty($order['notes'])): ?>
                        <p><strong>Ghi chú:</strong> <?= htmlspecialchars($order['notes']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="info-box">
                    <h4><i class="fas fa-credit-card text-primary"></i> Thanh Toán</h4>
                    <p><strong>Phương thức:</strong> 
                        <?= match($order['payment_method']) {
                            'cod' => 'Tiền mặt khi nhận xe (COD)',
                            'bank_transfer' => 'Chuyển khoản Ngân hàng',
                            'online_gateway' => 'Cổng thanh toán điện tử',
                            default => $order['payment_method']
                        } ?>
                    </p>
                    <p><strong>Trạng thái:</strong> 
                        <span class="badge-status status-<?= $order['payment_status'] ?>">
                            <?= $order['payment_status'] === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
                        </span>
                    </p>
                    <?php if (!empty($order['payment'])): ?>
                        <p><strong>Mã giao dịch:</strong> <?= htmlspecialchars($order['payment']['transaction_code']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($order['cancel_reason'])): ?>
                        <p class="text-danger"><strong>Lý do hủy:</strong> <?= htmlspecialchars($order['cancel_reason']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Items Table -->
            <div class="order-items-table-wrap mt-4">
                <h3>Danh Sách Xe Đặt Mua</h3>
                <table class="table-order-items">
                    <thead>
                        <tr>
                            <th>Xe Máy Điện & Phiên Bản</th>
                            <th>Đơn Giá</th>
                            <th>Số Lượng</th>
                            <th>Thành Tiền</th>
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

            <div class="detail-actions-bottom mt-4">
                <a href="/account/orders" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Quay Lại Danh Sách</a>
                <?php if ($order['order_status'] === 'pending'): ?>
                    <form action="/account/orders/cancel/<?= $order['id'] ?>" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');">
                        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Hủy Đơn Hàng</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
