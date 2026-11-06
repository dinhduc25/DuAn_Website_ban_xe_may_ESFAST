<div class="vouchers-page">
    <div class="breadcrumb-bar">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li class="active">Kho Voucher & Ưu Đãi</li>
            </ul>
        </div>
    </div>

    <div class="container vouchers-container">
        <div class="section-header text-center">
            <span class="sub-heading text-primary">ĐẶC QUYỀN MUA SẮM</span>
            <h1 class="section-title">Kho Mã Giảm Giá & Ưu Đãi Độc Quyền ESFAST</h1>
            <p class="section-desc">Sao chép mã voucher và áp dụng ngay tại bước thanh toán để nhận trợ giá trực tiếp khi mua xe máy điện.</p>
        </div>

        <?php if (!empty($vouchers)): ?>
            <div class="vouchers-grid">
                <?php foreach ($vouchers as $v): ?>
                    <div class="voucher-ticket">
                        <div class="ticket-left">
                            <div class="ticket-brand">ESFAST</div>
                            <div class="ticket-discount">
                                <?php if ($v['discount_type'] === 'percentage'): ?>
                                    <span class="amount"><?= (float)$v['discount_value'] ?>%</span>
                                    <span class="type">GIẢM</span>
                                <?php else: ?>
                                    <span class="amount"><?= number_format($v['discount_value'] / 1000, 0) ?>K</span>
                                    <span class="type">GIẢM</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="ticket-right">
                            <h3 class="ticket-name"><?= htmlspecialchars($v['name']) ?></h3>
                            <div class="ticket-condition">
                                <i class="fas fa-info-circle text-primary"></i> 
                                Đơn hàng từ <strong><?= number_format($v['min_order_amount'], 0, ',', '.') ?> VNĐ</strong>
                                <?php if (!empty($v['max_discount'])): ?>
                                    <br><small>Giảm tối đa <?= number_format($v['max_discount'], 0, ',', '.') ?> VNĐ</small>
                                <?php endif; ?>
                            </div>
                            <div class="ticket-date">
                                <i class="far fa-clock"></i> HSD: <?= date('d/m/Y', strtotime($v['end_date'])) ?>
                            </div>

                            <div class="ticket-action-bar">
                                <div class="voucher-code-tag">
                                    <span class="code-text" id="vcode_<?= $v['id'] ?>"><?= htmlspecialchars($v['code']) ?></span>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm btn-copy-code" onclick="copyVoucherCode('<?= htmlspecialchars($v['code']) ?>', this)">
                                    <i class="fas fa-copy"></i> Sao chép
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state-box">
                <div class="empty-icon"><i class="fas fa-tags"></i></div>
                <h3>Hiện tại không có mã giảm giá nào đang diễn ra</h3>
                <p>Hãy quay lại sau hoặc đăng ký nhận bản tin để đón nhận các sự kiện khuyến mãi sắp tới.</p>
                <a href="/products" class="btn btn-primary">Khám Phá Các Dòng Xe</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function copyVoucherCode(code, btn) {
    navigator.clipboard.writeText(code).then(() => {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Đã chép!';
        btn.classList.add('btn-success');
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('btn-success');
        }, 2000);
    }).catch(err => {
        alert('Mã của bạn: ' + code);
    });
}
</script>
