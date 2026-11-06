<div class="cart-page">
    <div class="breadcrumb-bar">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li class="active">Giỏ Hàng Mua Sắm</li>
            </ul>
        </div>
    </div>

    <div class="container cart-container">
        <h1 class="page-main-title"><i class="fas fa-shopping-bag text-primary"></i> Giỏ Hàng Của Bạn</h1>

        <?php if (!empty($items)): ?>
            <div class="cart-layout-grid">
                <!-- Left: Items Table / List -->
                <div class="cart-items-wrapper">
                    <div class="cart-table-card">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Xe Máy Điện & Phiên Bản</th>
                                    <th>Đơn Giá</th>
                                    <th>Số Lượng</th>
                                    <th>Thành Tiền</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $key => $item): ?>
                                    <tr>
                                        <td class="cart-product-cell">
                                            <div class="cart-prod-thumb">
                                                <img src="/assets/images/<?= htmlspecialchars($item['image']) ?>" 
                                                     alt="<?= htmlspecialchars($item['name']) ?>"
                                                     onerror="this.src='/assets/images/bike_fallback.png'">
                                            </div>
                                            <div class="cart-prod-info">
                                                <h4 class="cart-prod-name">
                                                    <a href="/products/<?= htmlspecialchars($item['slug'] ?? '') ?>"><?= htmlspecialchars($item['name']) ?></a>
                                                </h4>
                                                <?php if (!empty($item['variant_info'])): ?>
                                                    <span class="cart-variant-tag"><i class="fas fa-sliders-h"></i> <?= htmlspecialchars($item['variant_info']) ?></span>
                                                <?php endif; ?>
                                                <small class="cart-sku">SKU: <?= htmlspecialchars($item['sku']) ?></small>
                                            </div>
                                        </td>
                                        <td class="cart-price-cell">
                                            <?= number_format($item['price'], 0, ',', '.') ?> VNĐ
                                        </td>
                                        <td class="cart-qty-cell">
                                            <form action="/cart/update" method="POST" class="cart-qty-form">
                                                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                                <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                                                <div class="qty-btn-group">
                                                    <button type="submit" name="quantity" value="<?= $item['quantity'] - 1 ?>" class="btn-qty-mini" <?= $item['quantity'] <= 1 ? 'title="Xóa sản phẩm"' : '' ?>>
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="text" value="<?= $item['quantity'] ?>" readonly class="qty-val">
                                                    <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>" class="btn-qty-mini" <?= $item['quantity'] >= $item['max_stock'] ? 'disabled title="Đã đạt số lượng tồn kho tối đa"' : '' ?>>
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="cart-subtotal-cell">
                                            <strong><?= number_format($item['subtotal'], 0, ',', '.') ?> VNĐ</strong>
                                        </td>
                                        <td class="cart-remove-cell">
                                            <form action="/cart/remove" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa mẫu xe này khỏi giỏ hàng?');">
                                                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                                <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                                                <button type="submit" class="btn-remove-item" title="Xóa khỏi giỏ">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-actions-bottom">
                        <a href="/products" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Tiếp tục chọn xe</a>
                        <form action="/cart/clear" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn làm trống toàn bộ giỏ hàng?');">
                            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                            <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i> Xóa tất cả</button>
                        </form>
                    </div>
                </div>

                <!-- Right: Summary & Voucher Box -->
                <div class="cart-summary-wrapper">
                    <!-- Voucher Box -->
                    <div class="summary-card voucher-card">
                        <h3 class="summary-title"><i class="fas fa-ticket-alt text-primary"></i> Mã Giảm Giá / Voucher</h3>
                        
                        <?php if ($voucher): ?>
                            <div class="voucher-applied-box">
                                <div class="voucher-info">
                                    <span class="v-code"><?= htmlspecialchars($voucher['code']) ?></span>
                                    <span class="v-name"><?= htmlspecialchars($voucher['name']) ?></span>
                                    <span class="v-amount">-<?= number_format($discount, 0, ',', '.') ?> VNĐ</span>
                                </div>
                                <form action="/cart/voucher/remove" method="POST">
                                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                    <button type="submit" class="btn-remove-voucher" title="Gỡ mã giảm giá">&times;</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <form action="/cart/voucher/apply" method="POST" class="voucher-apply-form">
                                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                <div class="voucher-input-group">
                                    <input type="text" name="voucher_code" placeholder="Nhập mã voucher (VD: ESFAST2026)" required>
                                    <button type="submit" class="btn btn-primary">Áp Dụng</button>
                                </div>
                            </form>
                        <?php endif; ?>

                        <!-- Active vouchers suggestions -->
                        <?php if (!empty($activeVouchers)): ?>
                            <div class="suggested-vouchers">
                                <span class="suggest-title">Gợi ý mã giảm giá hôm nay:</span>
                                <?php foreach ($activeVouchers as $av): ?>
                                    <div class="suggest-item" onclick="quickApplyVoucher('<?= htmlspecialchars($av['code']) ?>')">
                                        <span class="s-code"><?= htmlspecialchars($av['code']) ?></span>
                                        <small class="s-desc">
                                            <?= $av['discount_type'] === 'percentage' ? 'Giảm ' . (float)$av['discount_value'] . '%' : 'Giảm ' . number_format($av['discount_value'], 0, ',', '.') . 'đ' ?> 
                                            (Đơn từ <?= number_format($av['min_order_amount'] / 1000000, 0) ?> triệu)
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Order Total Summary -->
                    <div class="summary-card total-card">
                        <h3 class="summary-title">Tóm Tắt Đơn Hàng</h3>
                        <div class="summary-line">
                            <span>Tạm tính giỏ hàng:</span>
                            <span class="val"><?= number_format($subtotal, 0, ',', '.') ?> VNĐ</span>
                        </div>
                        <?php if ($discount > 0): ?>
                            <div class="summary-line text-success">
                                <span>Giảm giá voucher:</span>
                                <span class="val">-<?= number_format($discount, 0, ',', '.') ?> VNĐ</span>
                            </div>
                        <?php endif; ?>
                        <div class="summary-line">
                            <span>Phí giao xe tận nhà:</span>
                            <span class="val text-success">MIỄN PHÍ</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-total-line">
                            <span>Tổng thanh toán:</span>
                            <strong class="total-price-final"><?= number_format($total, 0, ',', '.') ?> VNĐ</strong>
                        </div>
                        <p class="vat-note">(Đã bao gồm thuế giá trị gia tăng VAT 10%)</p>

                        <a href="/checkout" class="btn btn-primary btn-lg btn-block btn-checkout">
                            Tiến Hành Đặt Hàng <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty Cart -->
            <div class="empty-state-box empty-cart-box">
                <div class="empty-cart-illustration">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2>Giỏ hàng của bạn đang trống</h2>
                <p>Bạn chưa thêm mẫu xe máy điện nào vào giỏ hàng. Hãy khám phá bộ sưu tập xe thông minh ESFAST ngay!</p>
                <a href="/products" class="btn btn-primary btn-lg">
                    <i class="fas fa-motorcycle"></i> Khám Phá Các Dòng Xe
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function quickApplyVoucher(code) {
    const input = document.querySelector('input[name="voucher_code"]');
    if (input) {
        input.value = code;
        input.form.submit();
    }
}
</script>
