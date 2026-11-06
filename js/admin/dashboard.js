/**
 * ESFAST ADMIN - DASHBOARD SCRIPT (VANILLA JS)
 * Calculates KPIs, renders SVG/Canvas revenue chart, recent orders & best sellers
 */

function initAdminDashboard() {
    const adminUser = requireAdminAuth('../login.html');
    if (!adminUser) return;

    // Display admin profile info
    const adminNameEl = document.getElementById('adminTopbarName');
    if (adminNameEl) adminNameEl.textContent = adminUser.fullName || adminUser.username;

    const products = getProducts();
    const categories = getCategories();
    const orders = getOrders();
    const users = getUsers().filter(u => u.role === 'customer');

    // 1. KPI Calculations
    const totalProducts = products.length;
    const totalCategories = categories.length;
    const totalCustomers = users.length;
    const totalOrders = orders.length;

    // Total Simulated Revenue: from non-cancelled orders
    const totalRevenue = orders
        .filter(o => o.orderStatus !== 'cancelled')
        .reduce((sum, o) => sum + Number(o.totalAmount || 0), 0);

    // Status breakdown
    const processingOrders = orders.filter(o => o.orderStatus === 'processing').length;
    const shippingOrders = orders.filter(o => o.orderStatus === 'shipping').length;
    const completedOrders = orders.filter(o => o.orderStatus === 'completed').length;
    const cancelledOrders = orders.filter(o => o.orderStatus === 'cancelled').length;

    // Update KPI elements
    setStatText('kpiTotalRevenue', formatMoney(totalRevenue));
    setStatText('kpiTotalOrders', totalOrders);
    setStatText('kpiTotalProducts', totalProducts);
    setStatText('kpiTotalCustomers', totalCustomers);

    setStatText('kpiProcessing', processingOrders);
    setStatText('kpiShipping', shippingOrders);
    setStatText('kpiCompleted', completedOrders);
    setStatText('kpiCancelled', cancelledOrders);

    // 2. Render Revenue Bar Chart (Pure Canvas / SVG)
    renderRevenueChart(orders);

    // 3. Render Recent Orders Table
    renderRecentOrdersTable(orders.slice(0, 5));

    // 4. Render Top Selling Bikes
    renderTopSellingBikes(products, orders);
}

function setStatText(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
}

function renderRevenueChart(orders) {
    const chartContainer = document.getElementById('revenueChartCanvas');
    if (!chartContainer) return;

    const ctx = chartContainer.getContext('2d');
    const width = chartContainer.width = chartContainer.parentElement.clientWidth || 600;
    const height = chartContainer.height = 260;

    // Simulated 6 months data with real order addition
    const months = ['Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11 (Nay)'];
    const baseRevenues = [120000000, 185000000, 240000000, 310000000, 420000000, 0];

    // Calculate current month from orders
    const currentMonthRev = orders
        .filter(o => o.orderStatus !== 'cancelled')
        .reduce((sum, o) => sum + Number(o.totalAmount || 0), 0);

    baseRevenues[5] = currentMonthRev > 0 ? currentMonthRev : 485000000;

    const maxVal = Math.max(...baseRevenues) * 1.25;
    const paddingLeft = 50;
    const paddingBottom = 40;
    const chartWidth = width - paddingLeft - 20;
    const chartHeight = height - paddingBottom - 20;

    ctx.clearRect(0, 0, width, height);

    // Draw horizontal grid lines
    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 1;
    ctx.fillStyle = '#94a3b8';
    ctx.font = '11px sans-serif';

    for (let i = 0; i <= 4; i++) {
        const y = 20 + (chartHeight / 4) * i;
        const val = Math.round(maxVal - (maxVal / 4) * i);
        ctx.beginPath();
        ctx.moveTo(paddingLeft, y);
        ctx.lineTo(width - 20, y);
        ctx.stroke();
        ctx.fillText((val / 1000000).toFixed(0) + ' Tr', 10, y + 4);
    }

    // Draw Bars
    const barWidth = Math.min(45, (chartWidth / months.length) * 0.55);
    const spacing = chartWidth / months.length;

    months.forEach((m, idx) => {
        const x = paddingLeft + (idx * spacing) + (spacing - barWidth) / 2;
        const barH = (baseRevenues[idx] / maxVal) * chartHeight;
        const y = 20 + chartHeight - barH;

        // Gradient
        const gradient = ctx.createLinearGradient(0, y, 0, y + barH);
        gradient.addColorStop(0, idx === 5 ? '#00d2ff' : '#0052cc');
        gradient.addColorStop(1, idx === 5 ? '#0052cc' : '#1e293b');

        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.roundRect ? ctx.roundRect(x, y, barWidth, barH, [6, 6, 0, 0]) : ctx.fillRect(x, y, barWidth, barH);
        ctx.fill();

        // Month Label
        ctx.fillStyle = '#64748b';
        ctx.textAlign = 'center';
        ctx.fillText(m, x + barWidth / 2, height - 12);
    });
}

function renderRecentOrdersTable(orders) {
    const tableBody = document.getElementById('recentOrdersTableBody');
    if (!tableBody) return;

    if (orders.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">Chưa có đơn hàng nào.</td></tr>';
        return;
    }

    tableBody.innerHTML = orders.map(o => `
        <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 12px 16px; font-weight: 700; color: #0052cc;">${o.orderCode}</td>
            <td style="padding: 12px 16px;">
                <div style="font-weight: 600; color: #0f172a;">${o.customerName}</div>
                <div style="font-size: 0.8rem; color: #64748b;">${o.customerPhone}</div>
            </td>
            <td style="padding: 12px 16px; font-weight: 700; color: #0f172a;">${formatMoney(o.totalAmount)}</td>
            <td style="padding: 12px 16px;">${getStatusBadge(o.orderStatus)}</td>
            <td style="padding: 12px 16px; font-size: 0.85rem; color: #64748b;">${formatDate(o.createdAt)}</td>
            <td style="padding: 12px 16px; text-align: right;">
                <a href="orders.html?code=${o.orderCode}" class="btn btn-outline" style="padding: 5px 12px; font-size: 0.8rem; border-radius: 6px; text-decoration: none;">
                    Chi Tiết
                </a>
            </td>
        </tr>
    `).join('');
}

function renderTopSellingBikes(products, orders) {
    const container = document.getElementById('topSellingBikesContainer');
    if (!container) return;

    const list = products.slice(0, 4);
    container.innerHTML = list.map(p => `
        <div style="display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
            <img src="../${p.image}" alt="${p.name}" style="width: 50px; height: 50px; object-fit: contain; background: #f8fafc; border-radius: 8px; padding: 4px; border: 1px solid #e2e8f0;">
            <div style="flex: 1;">
                <h5 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: #0f172a;">${p.name}</h5>
                <span style="font-size: 0.8rem; color: #64748b;">Kho: <strong>${p.stockQuantity}</strong> chiếc</span>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 700; color: #0052cc; font-size: 0.95rem;">${formatMoney(p.salePrice || p.price)}</div>
                <span style="font-size: 0.75rem; color: #10b981; font-weight: 700;"><i class="fas fa-chart-line"></i> Bán chạy</span>
            </div>
        </div>
    `).join('');
}

function getStatusBadge(status) {
    if (status === 'processing') return '<span style="background: #eff6ff; color: #1d4ed8; padding: 3px 8px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">Đang chuẩn bị xe</span>';
    if (status === 'shipping') return '<span style="background: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">Đang giao</span>';
    if (status === 'completed') return '<span style="background: #ecfdf5; color: #065f46; padding: 3px 8px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">Hoàn thành</span>';
    if (status === 'cancelled') return '<span style="background: #fef2f2; color: #991b1b; padding: 3px 8px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">Đã hủy</span>';
    return '<span style="background: #f1f5f9; color: #475569; padding: 3px 8px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">Chờ xử lý</span>';
}

function adminLogout() {
    if (confirm('Bạn có chắc chắn muốn đăng xuất trang quản trị?')) {
        logoutUser();
        alert('Đã đăng xuất tài khoản quản trị!');
        window.location.href = '../login.html';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminDashboard();
});
