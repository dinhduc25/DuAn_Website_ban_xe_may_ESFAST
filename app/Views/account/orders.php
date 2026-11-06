<div class="account-page">
    <div class="breadcrumb-bar">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li><a href="/account">Tài Khoản</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li class="active">Lịch Sử Mua Hàng</li>
            </ul>
        </div>
    </div>

    <div class="container account-container">
        <div class="account-layout">
            <aside class="account-sidebar">
                <div class="account-user-card">
                    <div class="account-avatar"><i class="fas fa-user-circle"></i></div>
                    <h4><?= htmlspecialchars(\App\Core\Auth::user()['full_name']) ?></h4>
                    <span class="user-role-tag"><i class="fas fa-shield-alt"></i> Khách Hàng Thân Thiết</span>
                </div>
                <ul class="account-nav-list">
                    <li><a href="/account"><i class="fas fa-user-edit"></i> Thông Tin Cá Nhân</a></li>
                    <li><a href="/account/orders" class="active"><i class="fas fa-box"></i> Lịch Sử Mua Hàng</a></li>
                    <li><a href="/vouchers"><i class="fas fa-ticket-alt"></i> Kho Voucher Của Tôi</a></li>
                    <li><a href="/logout" class="text-danger"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a></li>
                </ul>
            </aside>

            <main class="account-main">
                <div class="account-card">
                    <div class="card-header-flex">
                        <h2 class="card-title"><i class="fas fa-clipboard-list text-primary"></i> Đơn Hàng Của Bạn</h2>
                        <span class="text-muted">Tổng cộng: <?= $pagination['total'] ?> đơn hàng</span>
                    </div>

                    <?php if (!empty($orders)): ?>
                        <div class="orders-list">
                            <?php foreach ($orders as $ord): ?>
                                <div class="order-card-item">
                                    <div class="order-card-header">
                                        <div class="order-h-left">
                                            <span class="order-code">#<?= htmlspecialchars($ord['order_code']) ?></span>
                                            <span class="order-date"><i class="far fa-clock"></i> <?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></span>
                                        </div>
                                        <div class="order-h-right">
                                            <span class="badge-status status-<?= $ord['order_status'] ?>">
                                                <?= match($ord['order_status']) {
                                                    'pending' => 'Chờ xử lý',
                                                    'processing' => 'Đang chuẩn bị',
                                                    'shipping' => 'Đang vận chuyển',
                                                    'completed' => 'Đã giao thành công',
                                                    'cancelled' => 'Đã hủy',
                                                    default => $ord['order_status']
                                                } ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="order-card-body">
                                        <?php if (!empty($ord['details'])): ?>
                                            <?php foreach ($ord['details'] as $item): ?>
                                                <div class="order-item-inline">
                                                    <div class="item-img">
                                                        <img src="/assets/images/<?= htmlspecialchars($item['product_image'] ?? 'bike_fallback.png') ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" onerror="this.src='/assets/images/bike_fallback.png'">
                                                    </div>
                                                    <div class="item-meta">
                                                        <h5><?= htmlspecialchars($item['product_name']) ?></h5>
                                                        <?php if (!empty($item['variant_info'])): ?>
                                                            <small><?= htmlspecialchars($item['variant_info']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="item-price-qty">
                                                        <span>x<?= $item['quantity'] ?></span>
                                                        <strong><?= number_format($item['total_price'], 0, ',', '.') ?> VNĐ</strong>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="order-card-footer">
                                        <div class="order-total-sum">
                                            <span>Tổng thanh toán:</span>
                                            <strong class="text-primary"><?= number_format($ord['total_amount'], 0, ',', '.') ?> VNĐ</strong>
                                        </div>
                                        <div class="order-card-actions">
                                            <a href="/account/orders/<?= htmlspecialchars($ord['order_code']) ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Xem Chi Tiết
                                            </a>
                                            <?php if ($ord['order_status'] === 'pending'): ?>
                                                <form action="/account/orders/cancel/<?= $ord['id'] ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                                                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-times"></i> Hủy Đơn
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($pagination['total_pages'] > 1): ?>
                            <div class="pagination-wrapper">
                                <ul class="pagination-list">
                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                        <li><a href="?page=<?= $i ?>" class="page-link <?= $i === $pagination['page'] ? 'active' : '' ?>"><?= $i ?></a></li>
                                    <?php endfor; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="empty-state-box">
                            <div class="empty-icon"><i class="fas fa-box-open"></i></div>
                            <h3>Bạn chưa có đơn đặt mua xe nào</h3>
                            <p>Hãy trải nghiệm và đặt mua xe máy điện thông minh ESFAST đầu tiên của bạn!</p>
                            <a href="/products" class="btn btn-primary"><i class="fas fa-motorcycle"></i> Khám Phá Sản Phẩm</a>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</div>
