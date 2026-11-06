<div class="dashboard-page">
    <!-- Top Stat KPI Cards Grid -->
    <div class="kpi-grid">
        <div class="kpi-card kpi-revenue">
            <div class="kpi-icon"><i class="fas fa-coins"></i></div>
            <div class="kpi-meta">
                <span class="kpi-label">Tổng Doanh Thu</span>
                <strong class="kpi-value"><?= number_format($stats['total_revenue'], 0, ',', '.') ?> VNĐ</strong>
                <span class="kpi-sub"><i class="fas fa-check-circle text-success"></i> Từ các đơn hoàn tất</span>
            </div>
        </div>

        <div class="kpi-card kpi-orders">
            <div class="kpi-icon"><i class="fas fa-shopping-bag"></i></div>
            <div class="kpi-meta">
                <span class="kpi-label">Tổng Đơn Hàng</span>
                <strong class="kpi-value"><?= number_format($stats['total_orders']) ?></strong>
                <span class="kpi-sub">
                    <span class="text-warning">Chờ: <?= $stats['pending_orders'] ?></span> • 
                    <span class="text-success">Xong: <?= $stats['completed_orders'] ?></span>
                </span>
            </div>
        </div>

        <div class="kpi-card kpi-products">
            <div class="kpi-icon"><i class="fas fa-motorcycle"></i></div>
            <div class="kpi-meta">
                <span class="kpi-label">Dòng Xe Đang Bán</span>
                <strong class="kpi-value"><?= number_format($stats['total_products']) ?></strong>
                <span class="kpi-sub"><?= $stats['total_categories'] ?> phân khúc xe điện</span>
            </div>
        </div>

        <div class="kpi-card kpi-users">
            <div class="kpi-icon"><i class="fas fa-users"></i></div>
            <div class="kpi-meta">
                <span class="kpi-label">Khách Hàng Đăng Ký</span>
                <strong class="kpi-value"><?= number_format($stats['total_customers']) ?></strong>
                <span class="kpi-sub"><i class="fas fa-user-check text-info"></i> Thành viên hệ sinh thái</span>
            </div>
        </div>
    </div>

    <!-- Status Breakdown Cards -->
    <div class="status-counters-bar mt-4">
        <div class="counter-pill status-pending">
            <i class="fas fa-clock"></i> Chờ Xử Lý: <strong><?= $stats['pending_orders'] ?></strong>
        </div>
        <div class="counter-pill status-processing">
            <i class="fas fa-cog"></i> Đang Chuẩn Bị Xe: <strong><?= $stats['processing_orders'] ?></strong>
        </div>
        <div class="counter-pill status-shipping">
            <i class="fas fa-shipping-fast"></i> Đang Vận Chuyển: <strong><?= $stats['shipping_orders'] ?></strong>
        </div>
        <div class="counter-pill status-completed">
            <i class="fas fa-check-circle"></i> Đã Giao Thành Công: <strong><?= $stats['completed_orders'] ?></strong>
        </div>
        <div class="counter-pill status-cancelled">
            <i class="fas fa-times-circle"></i> Đã Hủy: <strong><?= $stats['cancelled_orders'] ?></strong>
        </div>
    </div>

    <div class="dashboard-charts-grid mt-4">
        <!-- Monthly Revenue Chart -->
        <div class="admin-card chart-card">
            <div class="card-header-flex">
                <h3 class="card-title"><i class="fas fa-chart-bar text-primary"></i> Doanh Thu 6 Tháng Gần Nhất (Từ Database)</h3>
                <a href="/admin/reports" class="btn btn-outline-primary btn-sm">Xem chi tiết báo cáo</a>
            </div>
            <div class="chart-container">
                <?php if (!empty($stats['monthly_revenue'])): ?>
                    <div class="bar-chart-visual">
                        <?php 
                        $maxRev = 1;
                        foreach ($stats['monthly_revenue'] as $m) {
                            if ((float)$m['total_revenue'] > $maxRev) $maxRev = (float)$m['total_revenue'];
                        }
                        ?>
                        <?php foreach ($stats['monthly_revenue'] as $m): ?>
                            <?php $heightPct = max(8, round(((float)$m['total_revenue'] / $maxRev) * 100)); ?>
                            <div class="chart-bar-col">
                                <div class="bar-fill-wrap">
                                    <div class="bar-fill" style="height: <?= $heightPct ?>%;" title="<?= number_format($m['total_revenue'], 0, ',', '.') ?> VNĐ">
                                        <span class="bar-tooltip"><?= number_format($m['total_revenue'] / 1000000, 1) ?>M</span>
                                    </div>
                                </div>
                                <span class="bar-label"><?= htmlspecialchars($m['month_label']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Chưa có dữ liệu đơn hàng hoàn thành để vẽ biểu đồ.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions & System Info -->
        <div class="admin-card quick-actions-card">
            <h3 class="card-title"><i class="fas fa-bolt text-primary"></i> Tác Vụ Nhanh</h3>
            <div class="quick-links-grid">
                <a href="/admin/products/create" class="quick-link-btn">
                    <i class="fas fa-plus-circle text-primary"></i>
                    <span>Thêm Xe Mới</span>
                </a>
                <a href="/admin/orders?status=pending" class="quick-link-btn">
                    <i class="fas fa-bell text-warning"></i>
                    <span>Duyệt Đơn Chờ</span>
                </a>
                <a href="/admin/vouchers" class="quick-link-btn">
                    <i class="fas fa-tags text-success"></i>
                    <span>Tạo Voucher Mới</span>
                </a>
                <a href="/admin/content/contacts" class="quick-link-btn">
                    <i class="fas fa-envelope-open-text text-info"></i>
                    <span>Xem Yêu Cầu Tư Vấn</span>
                </a>
            </div>

            <div class="system-health-box mt-4">
                <h4><i class="fas fa-server"></i> Hệ Thống ESFAST</h4>
                <p><strong>Phiên bản:</strong> ESFAST v2.0 Production</p>
                <p><strong>Cơ sở dữ liệu:</strong> MySQL 8.0 (PDO Prepared)</p>
                <p><strong>Múi giờ:</strong> Asia/Ho_Chi_Minh</p>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="admin-card mt-4">
        <div class="card-header-flex">
            <h3 class="card-title"><i class="fas fa-history text-primary"></i> Đơn Hàng Mới Tiếp Nhận</h3>
            <a href="/admin/orders" class="btn btn-outline-primary btn-sm">Xem tất cả đơn hàng</a>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Số Điện Thoại</th>
                        <th>Tổng Tiền</th>
                        <th>Phương Thức</th>
                        <th>Thanh Toán</th>
                        <th>Trạng Thái</th>
                        <th>Thời Gian</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stats['recent_orders'])): ?>
                        <?php foreach ($stats['recent_orders'] as $ord): ?>
                            <tr>
                                <td><strong class="text-primary">#<?= htmlspecialchars($ord['order_code']) ?></strong></td>
                                <td><?= htmlspecialchars($ord['customer_name']) ?></td>
                                <td><?= htmlspecialchars($ord['customer_phone']) ?></td>
                                <td><strong><?= number_format($ord['total_amount'], 0, ',', '.') ?> đ</strong></td>
                                <td>
                                    <span class="badge-method"><?= strtoupper($ord['payment_method']) ?></span>
                                </td>
                                <td>
                                    <span class="badge-status status-<?= $ord['payment_status'] ?>">
                                        <?= $ord['payment_status'] === 'paid' ? 'Đã TT' : 'Chưa TT' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status status-<?= $ord['order_status'] ?>">
                                        <?= match($ord['order_status']) {
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'shipping' => 'Đang giao',
                                            'completed' => 'Hoàn tất',
                                            'cancelled' => 'Đã hủy',
                                            default => $ord['order_status']
                                        } ?>
                                    </span>
                                </td>
                                <td><small class="text-muted"><?= date('d/m H:i', strtotime($ord['created_at'])) ?></small></td>
                                <td>
                                    <a href="/admin/orders/<?= $ord['id'] ?>" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Chưa có đơn hàng nào được tạo.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
