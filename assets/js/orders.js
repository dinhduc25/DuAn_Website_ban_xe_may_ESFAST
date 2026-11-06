/**
 * ESFAST ECOMMERCE - ORDERS MANAGEMENT SCRIPT (VANILLA JS)
 * Handles customer order tracking, detail modal, cancellation
 */

function initOrdersPage() {
    const currentUser = getCurrentUser();
    const ordersContainer = document.getElementById('ordersListContainer');
    const emptyNotice = document.getElementById('ordersEmptyNotice');

    if (!ordersContainer) return;

    let orders = getOrders();

    // If logged in, filter orders belonging to this user
    if (currentUser) {
        orders = orders.filter(o => o.userId === currentUser.id || o.customerEmail === currentUser.email || o.customerPhone === currentUser.phone);
    }

    if (orders.length === 0) {
        if (emptyNotice) emptyNotice.style.display = 'block';
        ordersContainer.innerHTML = '';
        return;
    }

    if (emptyNotice) emptyNotice.style.display = 'none';

    ordersContainer.innerHTML = orders.map(order => {
        const statusBadge = getStatusBadge(order.orderStatus);
        const paymentBadge = getPaymentBadge(order.paymentStatus);
        const canCancel = order.orderStatus === 'processing';

        return `
            <div class="order-card" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                <!-- Header of Order Card -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <span style="font-size: 0.82rem; color: #64748b;">Mã đơn hàng:</span>
                        <strong style="font-size: 1.1rem; color: #0052cc; margin-left: 6px;">${order.orderCode}</strong>
                        <span style="font-size: 0.82rem; color: #94a3b8; margin-left: 12px;"><i class="far fa-clock"></i> ${formatDate(order.createdAt)}</span>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        ${statusBadge}
                        ${paymentBadge}
                    </div>
                </div>

                <!-- Items Preview -->
                <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                    ${order.items.map(item => `
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <img src="${item.image}" alt="${item.productName}" style="width: 50px; height: 50px; object-fit: contain; background: #f8fafc; border-radius: 8px; padding: 4px; border: 1px solid #e2e8f0;">
                            <div style="flex: 1;">
                                <div style="font-weight: 700; font-size: 0.95rem; color: #0f172a;">${item.productName}</div>
                                <div style="font-size: 0.8rem; color: #64748b;">${item.variantName || 'Tiêu chuẩn'} × <strong>${item.quantity}</strong></div>
                            </div>
                            <div style="font-weight: 700; color: #0052cc; font-size: 0.95rem;">
                                ${formatMoney(item.price * item.quantity)}
                            </div>
                        </div>
                    `).join('')}
                </div>

                <!-- Footer Summary & Actions -->
                <div style="border-top: 1px solid #f1f5f9; padding-top: 14px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <span style="font-size: 0.88rem; color: #64748b;">Tổng tiền thanh toán:</span>
                        <strong style="font-size: 1.25rem; color: #0052cc; margin-left: 8px;">${formatMoney(order.totalAmount)}</strong>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="button" onclick="showOrderDetailModal(${order.id})" class="btn btn-outline" style="padding: 7px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-eye"></i> Xem Chi Tiết
                        </button>
                        ${canCancel ? `
                            <button type="button" onclick="cancelOrder(${order.id})" class="btn btn-danger" style="background: #fee2e2; border: 1px solid #fca5a5; color: #ef4444; padding: 7px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-ban"></i> Hủy Đơn Hàng
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function getStatusBadge(status) {
    if (status === 'processing') return '<span style="background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700;"><i class="fas fa-spinner fa-spin"></i> Đang chuẩn bị xe</span>';
    if (status === 'shipping') return '<span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700;"><i class="fas fa-truck"></i> Đang vận chuyển</span>';
    if (status === 'completed') return '<span style="background: #ecfdf5; color: #065f46; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700;"><i class="fas fa-check-circle"></i> Đã hoàn thành</span>';
    if (status === 'cancelled') return '<span style="background: #fef2f2; color: #991b1b; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700;"><i class="fas fa-times-circle"></i> Đã hủy</span>';
    return '<span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700;">Chờ xử lý</span>';
}

function getPaymentBadge(status) {
    if (status === 'paid') return '<span style="background: #ecfdf5; color: #047857; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700;"><i class="fas fa-check"></i> Đã thanh toán</span>';
    return '<span style="background: #fffbeb; color: #b45309; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700;"><i class="fas fa-clock"></i> Chưa thanh toán</span>';
}

function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này? Thao tác này sẽ phục hồi số lượng tồn kho của mẫu xe.')) {
        return;
    }

    const orders = getOrders();
    const order = orders.find(o => o.id === Number(orderId));

    if (!order) {
        alert('Đơn hàng không tồn tại!');
        return;
    }

    if (order.orderStatus !== 'processing') {
        alert('Chỉ có thể hủy đơn hàng khi đang ở trạng thái chuẩn bị xe!');
        return;
    }

    order.orderStatus = 'cancelled';
    saveOrders(orders);

    // Restore inventory
    if (order.items && order.items.length > 0) {
        const products = getProducts();
        order.items.forEach(item => {
            const product = products.find(p => p.id === Number(item.productId));
            if (product) {
                product.stockQuantity += item.quantity;
                if (product.variants && item.variantId) {
                    const variant = product.variants.find(v => v.id === Number(item.variantId));
                    if (variant) {
                        variant.stock += item.quantity;
                    }
                }
            }
        });
        saveProducts(products);
    }

    alert('Đã hủy đơn hàng thành công!');
    initOrdersPage();
}

function showOrderDetailModal(orderId) {
    const orders = getOrders();
    const order = orders.find(o => o.id === Number(orderId));
    if (!order) return;

    const modal = document.getElementById('orderDetailModal');
    const content = document.getElementById('orderDetailModalContent');
    if (!modal || !content) return;

    content.innerHTML = `
        <div style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 16px;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">Chi Tiết Đơn Hàng: ${order.orderCode}</h3>
                    <div style="font-size: 0.82rem; color: #64748b; margin-top: 4px;">Thời gian đặt: ${formatDate(order.createdAt)}</div>
                </div>
                <button type="button" onclick="closeOrderDetailModal()" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer;">&times;</button>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; font-size: 0.88rem; background: #f8fafc; border-radius: 10px; padding: 14px;">
                <div>
                    <div style="color: #64748b; margin-bottom: 2px;">Khách hàng:</div>
                    <strong style="color: #0f172a;">${order.customerName}</strong>
                    <div style="color: #64748b; margin-top: 8px; margin-bottom: 2px;">Số điện thoại:</div>
                    <strong style="color: #0f172a;">${order.customerPhone}</strong>
                </div>
                <div>
                    <div style="color: #64748b; margin-bottom: 2px;">Địa chỉ bàn giao xe:</div>
                    <strong style="color: #0f172a;">${order.shippingAddress}</strong>
                    <div style="color: #64748b; margin-top: 8px; margin-bottom: 2px;">Phương thức thanh toán:</div>
                    <strong style="color: #0052cc;">${order.paymentMethod === 'cod' ? 'Tiền mặt khi giao xe' : order.paymentMethod === 'bank_transfer' ? 'Chuyển khoản Vietcombank' : 'Cổng VNPAY'}</strong>
                </div>
            </div>

            <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 10px;">Danh Sách Xe Đặt Mua:</h4>
            <div style="margin-bottom: 20px;">
                ${order.items.map(item => `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                        <div>
                            <strong style="color: #0f172a;">${item.productName}</strong>
                            <div style="font-size: 0.8rem; color: #64748b;">${item.variantName || 'Tiêu chuẩn'} × ${item.quantity}</div>
                        </div>
                        <div style="font-weight: 700; color: #0052cc;">${formatMoney(item.price * item.quantity)}</div>
                    </div>
                `).join('')}
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.9rem; border-top: 1px solid #e2e8f0; padding-top: 14px;">
                <div style="display: flex; justify-content: space-between; color: #64748b;">
                    <span>Tạm tính:</span>
                    <strong>${formatMoney(order.subtotal)}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; color: #64748b;">
                    <span>Giảm giá (Voucher ${order.voucherCode || 'Không có'}):</span>
                    <strong style="color: #ef4444;">-${formatMoney(order.discountAmount || 0)}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 800; color: #0f172a; border-top: 1px dashed #cbd5e1; padding-top: 8px;">
                    <span>Tổng thanh toán:</span>
                    <strong style="color: #0052cc;">${formatMoney(order.totalAmount)}</strong>
                </div>
            </div>
        </div>
    `;

    modal.style.display = 'flex';
}

function closeOrderDetailModal() {
    const modal = document.getElementById('orderDetailModal');
    if (modal) modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    initOrdersPage();
});
