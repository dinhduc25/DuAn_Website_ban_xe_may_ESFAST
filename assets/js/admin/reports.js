/**
 * ESFAST ADMIN - REPORTS & CSV EXPORT SCRIPT (VANILLA JS)
 * Financial summaries, order metrics, date range filtering, pure client-side CSV generator
 */

function initAdminReports() {
    const adminUser = requireAdminAuth('../login.html');
    if (!adminUser) return;

    renderReportMetrics();
}

function renderReportMetrics() {
    const orders = getOrders();
    const period = document.getElementById('reportPeriodFilter')?.value || 'all';

    // Calculate metrics
    let filteredOrders = orders;
    const now = new Date();

    if (period === 'month') {
        const currentMonth = now.getMonth();
        const currentYear = now.getFullYear();
        filteredOrders = orders.filter(o => {
            const d = new Date(o.createdAt);
            return d.getMonth() === currentMonth && d.getFullYear() === currentYear;
        });
    } else if (period === 'year') {
        const currentYear = now.getFullYear();
        filteredOrders = orders.filter(o => {
            const d = new Date(o.createdAt);
            return d.getFullYear() === currentYear;
        });
    }

    const totalOrders = filteredOrders.length;
    const completedOrders = filteredOrders.filter(o => o.orderStatus === 'completed').length;
    const cancelledOrders = filteredOrders.filter(o => o.orderStatus === 'cancelled').length;
    const totalRevenue = filteredOrders
        .filter(o => o.orderStatus !== 'cancelled')
        .reduce((sum, o) => sum + Number(o.totalAmount || 0), 0);

    const avgOrderValue = totalOrders > 0 ? totalRevenue / (totalOrders - cancelledOrders || 1) : 0;

    // Display
    document.getElementById('repTotalRevenue').textContent = formatMoney(totalRevenue);
    document.getElementById('repTotalOrders').textContent = totalOrders;
    document.getElementById('repCompletedOrders').textContent = completedOrders;
    document.getElementById('repCancelledOrders').textContent = cancelledOrders;
    document.getElementById('repAvgValue').textContent = formatMoney(avgOrderValue);

    // Render detailed report table
    const tbody = document.getElementById('reportOrdersTableBody');
    if (tbody) {
        tbody.innerHTML = filteredOrders.map(o => `
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 10px 14px; font-weight: 700; color: #0052cc;">${o.orderCode}</td>
                <td style="padding: 10px 14px;">${o.customerName}</td>
                <td style="padding: 10px 14px;">${o.items.map(i => `${i.productName} (×${i.quantity})`).join(', ')}</td>
                <td style="padding: 10px 14px; font-weight: 700; color: #0f172a;">${formatMoney(o.totalAmount)}</td>
                <td style="padding: 10px 14px;">${o.paymentMethod.toUpperCase()}</td>
                <td style="padding: 10px 14px;">${o.orderStatus.toUpperCase()}</td>
                <td style="padding: 10px 14px; font-size: 0.85rem; color: #64748b;">${formatDate(o.createdAt)}</td>
            </tr>
        `).join('');
    }
}

// PURE CLIENT-SIDE CSV EXPORT
function exportOrdersToCSV() {
    const orders = getOrders();
    if (orders.length === 0) {
        alert('Không có dữ liệu đơn hàng để xuất!');
        return;
    }

    // CSV Header (UTF-8 BOM for Excel Vietnamese compatibility)
    let csvContent = '\uFEFF';
    csvContent += 'Mã Đơn Hàng,Khách Hàng,Số Điện Thoại,Email,Địa Chỉ,Sản Phẩm Đặt,Tạm Tính,Giảm Giá,Tổng Tiền,Thanh Toán,Trạng Thái Đơn,Ngày Đặt\n';

    orders.forEach(o => {
        const itemsStr = o.items.map(i => `${i.productName} (x${i.quantity})`).join('; ').replace(/"/g, '""');
        const row = [
            `"${o.orderCode}"`,
            `"${o.customerName}"`,
            `"${o.customerPhone}"`,
            `"${o.customerEmail}"`,
            `"${(o.shippingAddress || '').replace(/"/g, '""')}"`,
            `"${itemsStr}"`,
            o.subtotal,
            o.discountAmount || 0,
            o.totalAmount,
            `"${o.paymentMethod}"`,
            `"${o.orderStatus}"`,
            `"${o.createdAt}"`
        ];
        csvContent += row.join(',') + '\n';
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', `Bao_Cao_Don_Hang_ESFAST_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showToast('Đã xuất báo cáo CSV thành công!', 'success');
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminReports();
});
