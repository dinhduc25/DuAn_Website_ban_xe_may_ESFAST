/**
 * ESFAST ADMIN - ORDERS MANAGEMENT (VANILLA JS)
 * Full order workflow: search, filter, status transition, payment update, inventory sync
 */

let viewingOrderId = null;

function initAdminOrders() {
    const adminUser = requireAdminAuth('../login.html');
    if (!adminUser) return;

    // Check query param for code
    const urlParams = new URLSearchParams(window.location.search);
    const codeParam = urlParams.get('code');
    if (codeParam) {
        const searchInput = document.getElementById('adminOrderSearch');
        if (searchInput) searchInput.value = codeParam;
    }

    renderOrdersTable();
}

function renderOrdersTable() {
    const orders = getOrders();
    const search = document.getElementById('adminOrderSearch')?.value.trim().toLowerCase() || '';
    const statusFilter = document.getElementById('adminOrderStatusFilter')?.value || 'all';
    const paymentFilter = document.getElementById('adminOrderPaymentFilter')?.value || 'all';

    const filtered = orders.filter(o => {
        if (statusFilter !== 'all' && o.orderStatus !== statusFilter) return false;
        if (paymentFilter !== 'all' && o.paymentStatus !== paymentFilter) return false;
        if (search) {
            const matchCode = o.orderCode.toLowerCase().includes(search);
            const matchName = o.customerName.toLowerCase().includes(search);
            const matchPhone = o.customerPhone.includes(search);
            if (!matchCode && !matchName && !matchPhone) return false;
        }
        return true;
    });

    const tbody = document.getElementById('adminOrdersTableBody');
    if (!tbody) return;

    if (filtered.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">Không tìm thấy đơn đặt xe nào.</td></tr>';
        return;
    }

    tbody.innerHTML = filtered.map(o => `
        <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 12px 16px;">
                <strong style="color: #0052cc;">${o.orderCode}</strong>
                <div style="font-size: 0.78rem; color: #94a3b8;">${formatDate(o.createdAt)}</div>
            </td>
            <td style="padding: 12px 16px;">
                <div style="font-weight: 700; color: #0f172a;">${o.customerName}</div>
                <div style="font-size: 0.8rem; color: #64748b;">${o.customerPhone}</div>
            </td>
            <td style="padding: 12px 16px; font-size: 0.85rem;">
                ${o.items.map(item => `<div>${item.productName} (×${item.quantity})</div>`).join('')}
            </td>
            <td style="padding: 12px 16px; font-weight: 700; color: #0f172a;">
                ${formatMoney(o.totalAmount)}
            </td>
            <td style="padding: 12px 16px;">
                <button type="button" onclick="togglePaymentStatus(${o.id})" style="border: none; background: ${o.paymentStatus === 'paid' ? '#ecfdf5' : '#fffbeb'}; color: ${o.paymentStatus === 'paid' ? '#047857' : '#b45309'}; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; cursor: pointer;">
                    ${o.paymentStatus === 'paid' ? '<i class="fas fa-check"></i> Đã thanh toán' : '<i class="fas fa-clock"></i> Chưa thanh toán'}
                </button>
            </td>
            <td style="padding: 12px 16px;">
                ${getStatusDropdown(o)}
            </td>
            <td style="padding: 12px 16px; text-align: right;">
                <button type="button" onclick="openAdminOrderDetail(${o.id})" class="btn btn-outline" style="padding: 5px 12px; font-size: 0.82rem; border-radius: 6px;">
                    <i class="fas fa-eye"></i> Chi tiết
                </button>
            </td>
        </tr>
    `).join('');
}

function getStatusDropdown(order) {
    return `
        <select onchange="updateOrderStatus(${order.id}, this.value)" style="padding: 5px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.8rem; font-weight: 600; outline: none; background: #ffffff;">
            <option value="processing" ${order.orderStatus === 'processing' ? 'selected' : ''}>Đang chuẩn bị xe</option>
            <option value="shipping" ${order.orderStatus === 'shipping' ? 'selected' : ''}>Đang giao xe</option>
            <option value="completed" ${order.orderStatus === 'completed' ? 'selected' : ''}>Đã hoàn thành</option>
            <option value="cancelled" ${order.orderStatus === 'cancelled' ? 'selected' : ''}>Đã hủy đơn</option>
        </select>
    `;
}

function updateOrderStatus(orderId, newStatus) {
    const orders = getOrders();
    const order = orders.find(o => o.id === Number(orderId));
    if (!order) return;

    const oldStatus = order.orderStatus;
    order.orderStatus = newStatus;

    // Inventory handling if cancelled
    if (newStatus === 'cancelled' && oldStatus !== 'cancelled') {
        const products = getProducts();
        order.items.forEach(item => {
            const p = products.find(x => x.id === item.productId);
            if (p) {
                p.stockQuantity += item.quantity;
                if (p.variants && item.variantId) {
                    const v = p.variants.find(va => va.id === item.variantId);
                    if (v) v.stock += item.quantity;
                }
            }
        });
        saveProducts(products);
        showToast('Đã hủy đơn và phục hồi số lượng tồn kho xe!', 'info');
    }

    saveOrders(orders);
    showToast(`Đã chuyển đơn ${order.orderCode} sang trạng thái mới!`, 'success');
    renderOrdersTable();
}

function togglePaymentStatus(orderId) {
    const orders = getOrders();
    const order = orders.find(o => o.id === Number(orderId));
    if (!order) return;

    order.paymentStatus = order.paymentStatus === 'paid' ? 'unpaid' : 'paid';
    saveOrders(orders);
    showToast(`Đã cập nhật trạng thái thanh toán mô phỏng cho đơn ${order.orderCode}!`, 'success');
    renderOrdersTable();
}

function openAdminOrderDetail(id) {
    viewingOrderId = id;
    const orders = getOrders();
    const order = orders.find(o => o.id === Number(id));
    if (!order) return;

    const modal = document.getElementById('adminOrderDetailModal');
    const content = document.getElementById('adminOrderDetailContent');

    content.innerHTML = `
        <div style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 1.3rem; font-weight: 800; color: #0f172a;">Đơn Hàng: ${order.orderCode}</h3>
                <button type="button" onclick="closeAdminOrderDetail()" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer;">&times;</button>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 0.9rem; margin-bottom: 24px; background: #f8fafc; padding: 16px; border-radius: 10px;">
                <div>
                    <div style="color: #64748b;">Họ tên khách: <strong style="color: #0f172a;">${order.customerName}</strong></div>
                    <div style="color: #64748b; margin-top: 6px;">Số điện thoại: <strong style="color: #0f172a;">${order.customerPhone}</strong></div>
                    <div style="color: #64748b; margin-top: 6px;">Email: <strong style="color: #0f172a;">${order.customerEmail}</strong></div>
                </div>
                <div>
                    <div style="color: #64748b;">Địa chỉ giao: <strong style="color: #0f172a;">${order.shippingAddress}</strong></div>
                    <div style="color: #64748b; margin-top: 6px;">Ghi chú: <em>${order.notes || 'Không có ghi chú'}</em></div>
                    <div style="color: #64748b; margin-top: 6px;">Phương thức: <strong>${order.paymentMethod}</strong></div>
                </div>
            </div>

            <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Sản phẩm đặt:</h4>
            <div style="margin-bottom: 24px;">
                ${order.items.map(item => `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                        <div>
                            <strong>${item.productName}</strong>
                            <div style="font-size: 0.8rem; color: #64748b;">${item.variantName || 'Tiêu chuẩn'} × ${item.quantity}</div>
                        </div>
                        <div style="font-weight: 700; color: #0052cc;">${formatMoney(item.price * item.quantity)}</div>
                    </div>
                `).join('')}
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 800; border-top: 2px dashed #cbd5e1; padding-top: 14px;">
                <span>Tổng giá trị đơn:</span>
                <span style="color: #0052cc;">${formatMoney(order.totalAmount)}</span>
            </div>
        </div>
    `;

    modal.style.display = 'flex';
}

function closeAdminOrderDetail() {
    document.getElementById('adminOrderDetailModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminOrders();
});
