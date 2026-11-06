<div class="checkout-page">
    <div class="breadcrumb-bar">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li><a href="/cart">Giỏ Hàng</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li class="active">Thanh Toán Đặt Xe</li>
            </ul>
        </div>
    </div>

    <div class="container checkout-container">
        <h1 class="page-main-title"><i class="fas fa-lock text-primary"></i> Đặt Hàng & Giao Nhận Xe</h1>

        <form action="/checkout" method="POST" id="checkoutForm">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

            <div class="checkout-grid">
                <!-- Left: Customer Information & Shipping -->
                <div class="checkout-form-column">
                    <div class="checkout-card">
                        <div class="card-header-styled">
                            <span class="step-num">1</span>
                            <h3>Thông Tin Người Nhận & Địa Chỉ Bàn Giao</h3>
                        </div>
                        <div class="card-body-styled">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-label" for="customer_name">Họ và Tên Quý Khách <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control" 
                                           placeholder="Ví dụ: Nguyễn Văn An" 
                                           value="<?= htmlspecialchars($old['customer_name'] ?? $user['full_name'] ?? '') ?>" required>
                                    <?php if (!empty($errors['customer_name'])): ?>
                                        <span class="field-error"><?= htmlspecialchars($errors['customer_name']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-row two-cols">
                                <div class="form-group">
                                    <label class="form-label" for="customer_phone">Số Điện Thoại <span class="text-danger">*</span></label>
                                    <input type="tel" name="customer_phone" id="customer_phone" class="form-control" 
                                           placeholder="Ví dụ: 0912345678" 
                                           value="<?= htmlspecialchars($old['customer_phone'] ?? $user['phone'] ?? '') ?>" required>
                                    <?php if (!empty($errors['customer_phone'])): ?>
                                        <span class="field-error"><?= htmlspecialchars($errors['customer_phone']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="customer_email">Địa Chỉ Email <span class="text-danger">*</span></label>
                                    <input type="email" name="customer_email" id="customer_email" class="form-control" 
                                           placeholder="Ví dụ: an.nguyen@gmail.com" 
                                           value="<?= htmlspecialchars($old['customer_email'] ?? $user['email'] ?? '') ?>" required>
                                    <?php if (!empty($errors['customer_email'])): ?>
                                        <span class="field-error"><?= htmlspecialchars($errors['customer_email']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-label" for="shipping_address">Địa Chỉ Nhận Bàn Giao Xe <span class="text-danger">*</span></label>
                                    <textarea name="shipping_address" id="shipping_address" rows="3" class="form-control" 
                                              placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..." required><?= htmlspecialchars($old['shipping_address'] ?? $user['address'] ?? '') ?></textarea>
                                    <?php if (!empty($errors['shipping_address'])): ?>
                                        <span class="field-error"><?= htmlspecialchars($errors['shipping_address']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-label" for="notes">Ghi Chú Đơn Hàng (Nếu có)</label>
                                    <textarea name="notes" id="notes" rows="2" class="form-control" 
                                              placeholder="Ví dụ: Thời gian giao xe thuận tiện, yêu cầu hỗ trợ biển số..."><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Section -->
                    <div class="checkout-card mt-4">
                        <div class="card-header-styled">
                            <span class="step-num">2</span>
                            <h3>Phương Thức Thanh Toán</h3>
                        </div>
                        <div class="card-body-styled">
                            <div class="payment-methods-list">
                                <!-- Option 1: COD -->
                                <label class="payment-option-card">
                                    <input type="radio" name="payment_method" value="cod" checked>
                                    <div class="payment-option-inner">
                                        <div class="payment-icon text-primary"><i class="fas fa-hand-holding-usd"></i></div>
                                        <div class="payment-text">
                                            <strong>Thanh toán tiền mặt khi nhận bàn giao xe (COD)</strong>
                                            <p>Quý khách kiểm tra xe, chạy thử và thanh toán trực tiếp cho chuyên viên giao xe của ESFAST.</p>
                                        </div>
                                    </div>
                                </label>

                                <!-- Option 2: Bank Transfer -->
                                <label class="payment-option-card">
                                    <input type="radio" name="payment_method" value="bank_transfer">
                                    <div class="payment-option-inner">
                                        <div class="payment-icon text-success"><i class="fas fa-university"></i></div>
                                        <div class="payment-text">
                                            <strong>Chuyển khoản qua Ngân hàng (Internet Banking / QR Code)</strong>
                                            <p>Chuyển khoản trực tiếp tới tài khoản công ty ESFAST Việt Nam. Xác nhận tự động qua mã đơn.</p>
                                        </div>
                                    </div>
                                </label>

                                <!-- Option 3: Online Gateway -->
                                <label class="payment-option-card">
                                    <input type="radio" name="payment_method" value="online_gateway">
                                    <div class="payment-option-inner">
                                        <div class="payment-icon text-info"><i class="fas fa-credit-card"></i></div>
                                        <div class="payment-text">
                                            <strong>Cổng thanh toán điện tử (VNPAY / Thẻ ATM / Visa / Mastercard)</strong>
                                            <p>Thanh toán an toàn bảo mật chuẩn quốc tế qua cổng liên kết ngân hàng.</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary Sticky Card -->
                <div class="checkout-summary-column">
                    <div class="summary-card sticky-card">
                        <h3 class="summary-title">Đơn Hàng Của Bạn (<?= count($items) ?> mẫu xe)</h3>
                        
                        <div class="checkout-items-list">
                            <?php foreach ($items as $item): ?>
                                <div class="checkout-item-row">
                                    <div class="item-thumb">
                                        <img src="/assets/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" onerror="this.src='/assets/images/bike_fallback.png'">
                                    </div>
                                    <div class="item-details">
                                        <h4><?= htmlspecialchars($item['name']) ?></h4>
                                        <?php if (!empty($item['variant_info'])): ?>
                                            <small><?= htmlspecialchars($item['variant_info']) ?></small>
                                        <?php endif; ?>
                                        <div class="item-qty-price">
                                            <span>Số lượng: <?= $item['quantity'] ?></span>
                                            <strong><?= number_format($item['subtotal'], 0, ',', '.') ?> VNĐ</strong>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="summary-breakdown">
                            <div class="breakdown-row">
                                <span>Tạm tính tiền xe:</span>
                                <span><?= number_format($subtotal, 0, ',', '.') ?> VNĐ</span>
                            </div>
                            <?php if ($voucher && $discount > 0): ?>
                                <div class="breakdown-row text-success">
                                    <span>Voucher (<?= htmlspecialchars($voucher['code']) ?>):</span>
                                    <span>-<?= number_format($discount, 0, ',', '.') ?> VNĐ</span>
                                </div>
                            <?php endif; ?>
                            <div class="breakdown-row">
                                <span>Phí giao xe toàn quốc:</span>
                                <span class="text-success">MIỄN PHÍ</span>
                            </div>
                            <div class="summary-divider"></div>
                            <div class="total-row">
                                <span>Tổng cộng:</span>
                                <strong class="total-val"><?= number_format($total, 0, ',', '.') ?> VNĐ</strong>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block btn-confirm-order">
                            <i class="fas fa-shield-alt"></i> Xác Nhận Đặt Mua Xe
                        </button>
                        
                        <div class="checkout-guarantee">
                            <i class="fas fa-lock"></i> Thông tin thanh toán của quý khách được bảo mật tuyệt đối với chứng chỉ mã hóa SSL 256-bit.
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
