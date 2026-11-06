<div class="admin-reports-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-chart-line text-primary"></i> Báo Cáo & Thống Kê Doanh Thu</h1>
    </div>

    <!-- Date Filter Form -->
    <div class="admin-card toolbar-card mb-4">
        <form action="/admin/reports" method="GET" class="admin-filter-form">
            <div class="filter-col">
                <label>Từ ngày:</label>
                <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
            </div>
            <div class="filter-col">
                <label>Đến ngày:</label>
                <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
            </div>
            <div class="filter-col pt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Lọc Báo Cáo</button>
                <a href="/admin/reports" class="btn btn-outline-secondary">Tháng này</a>
            </div>
        </form>
    </div>

    <!-- Period KPI Summary -->
    <div class="kpi-grid">
        <div class="kpi-card kpi-revenue">
            <div class="kpi-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="kpi-meta">
                <span class="kpi-label">Doanh Thu Giai Đoạn Lọc</span>
                <strong class="kpi-value"><?= number_format($totalPeriodRevenue, 0, ',', '.') ?> VNĐ</strong>
                <span class="kpi-sub"><?= date('d/m/Y', strtotime($startDate)) ?> – <?= date('d/m/Y', strtotime($endDate)) ?></span>
            </div>
        </div>

        <div class="kpi-card kpi-orders">
            <div class="kpi-icon"><i class="fas fa-check-circle"></i></div>
            <div class="kpi-meta">
                <span class="kpi-label">Số Đơn Hoàn Thành</span>
                <strong class="kpi-value"><?= number_format($totalPeriodOrders) ?> đơn</strong>
                <span class="kpi-sub">Đã bàn giao xe & thanh toán</span>
            </div>
        </div>

        <div class="kpi-card kpi-products">
            <div class="kpi-icon"><i class="fas fa-calculator"></i></div>
            <div class="kpi-meta">
                <span class="kpi-label">Giá Trị Đơn Trung Bình (AOV)</span>
                <strong class="kpi-value">
                    <?= $totalPeriodOrders > 0 ? number_format($totalPeriodRevenue / $totalPeriodOrders, 0, ',', '.') : 0 ?> VNĐ
                </strong>
                <span class="kpi-sub">Doanh số bình quân / đơn xe</span>
            </div>
        </div>
    </div>

    <div class="report-tables-grid mt-4">
        <!-- Top Best Selling Electric Bikes -->
        <div class="admin-card">
            <h3 class="card-title"><i class="fas fa-trophy text-warning"></i> Top Dòng Xe Bán Chạy Nhất</h3>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Mẫu Xe</th>
                            <th>Mã SKU</th>
                            <th>Số Lượng Bán</th>
                            <th>Doanh Thu Đạt Được</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topProducts)): ?>
                            <?php foreach ($topProducts as $top): ?>
                                <tr>
                                    <td>
                                        <div class="table-thumb">
                                            <img src="/assets/images/<?= htmlspecialchars($top['image']) ?>" alt="<?= htmlspecialchars($top['name']) ?>" onerror="this.src='/assets/images/bike_fallback.png'">
                                        </div>
                                    </td>
                                    <td><strong><?= htmlspecialchars($top['name']) ?></strong></td>
                                    <td><code><?= htmlspecialchars($top['sku']) ?></code></td>
                                    <td><span class="badge-count"><?= $top['total_sold'] ?> xe</span></td>
                                    <td><strong class="text-primary"><?= number_format($top['total_sales'], 0, ',', '.') ?> VNĐ</strong></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Chưa có dữ liệu bán hàng hoàn tất.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Daily Sales Breakdown Table -->
        <div class="admin-card">
            <h3 class="card-title"><i class="fas fa-calendar-day text-primary"></i> Chi Tiết Doanh Thu Theo Ngày</h3>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Số Đơn Hàng</th>
                            <th>Doanh Thu Trong Ngày</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dailyStats)): ?>
                            <?php foreach ($dailyStats as $row): ?>
                                <tr>
                                    <td><strong><?= date('d/m/Y', strtotime($row['sale_date'])) ?></strong></td>
                                    <td><?= $row['order_count'] ?> đơn</td>
                                    <td><strong class="text-success"><?= number_format($row['daily_revenue'], 0, ',', '.') ?> VNĐ</strong></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Không có đơn hàng hoàn thành trong khoảng thời gian này.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
