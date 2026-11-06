/**
 * ESFAST ECOMMERCE - CHECKOUT SCRIPT (VANILLA JS)
 * Handles customer form, simulated payments (COD, Bank, Gateway), inventory decrement, order creation
 */

let checkoutVoucher = null;

function initCheckoutPage() {
    const cart = getCart();
    if (cart.length === 0) {
        alert('Giỏ hàng của bạn đang trống! Hệ thống sẽ đưa bạn về trang sản phẩm.');
        window.location.href = 'products.html';
        return;
    }

    // Auto-fill if user logged in
    const currentUser = getCurrentUser();
    if (currentUser) {
        const nameInput = document.getElementById('checkoutFullName');
        const emailInput = document.getElementById('checkoutEmail');
        const phoneInput = document.getElementById('checkoutPhone');
        const addressInput = document.getElementById('checkoutAddress');

        if (nameInput) nameInput.value = currentUser.fullName || '';
        if (emailInput) emailInput.value = currentUser.email || '';
        if (phoneInput) phoneInput.value = currentUser.phone || '';
        if (addressInput && currentUser.address && !currentUser.address.includes('Chưa cập nhật')) {
            addressInput.value = currentUser.address;
        }
    }

    // Load active voucher draft if any
    try {
        const draft = JSON.parse(sessionStorage.getItem('esfast_checkout_draft'));
        if (draft && draft.voucherCode) {
            const vouchers = getVouchers();
            checkoutVoucher = vouchers.find(v => v.code === draft.voucherCode && v.isActive);
        }
    } catch (e) {}

    renderCheckoutReview();
}

function renderCheckoutReview() {
    const cart = getCart();
    const itemsContainer = document.getElementById('checkoutOrderItems');
    if (!itemsContainer) return;

    itemsContainer.innerHTML = cart.map(item => `
        <div style="display: flex; gap: 14px; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
            <div style="width: 56px; height: 56px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; padding: 4px;">
                <img src="${item.image}" alt="${item.productName}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
            </div>
            <div style="flex: 1;">
                <div style="font-weight: 700; font-size: 0.95rem; color: #0f172a; margin-bottom: 2px;">${item.productName}</div>
                <div style="font-size: 0.8rem; color: #64748b;">${item.variantName || 'Tiêu chuẩn'} × <strong>${item.quantity}</strong></div>
            </div>
            <div style="font-weight: 700; font-size: 0.95rem; color: #0052cc;">
                ${formatMoney(item.price * item.quantity)}
            </div>
        </div>
    `).join('');

    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let discount = 0;

    if (checkoutVoucher) {
        if (subtotal >= checkoutVoucher.minOrder) {
            if (checkoutVoucher.type === 'percentage') {
                discount = (subtotal * checkoutVoucher.value) / 100;
                if (checkoutVoucher.maxDiscount && discount > checkoutVoucher.maxDiscount) {
                    discount = checkoutVoucher.maxDiscount;
                }
            } else {
                discount = checkoutVoucher.value;
            }
        } else {
            checkoutVoucher = null;
        }
    }

    const total = Math.max(0, subtotal - discount);

    const subtotalEl = document.getElementById('checkoutSubtotal');
    const discountEl = document.getElementById('checkoutDiscount');
    const totalEl = document.getElementById('checkoutTotal');

    if (subtotalEl) subtotalEl.textContent = formatMoney(subtotal);
    if (discountEl) discountEl.textContent = discount > 0 ? `-${formatMoney(discount)}` : '0 ₫';
    if (totalEl) totalEl.textContent = formatMoney(total);
}

function handlePaymentMethodChange() {
    const selected = document.querySelector('input[name="paymentMethod"]:checked')?.value;
    const bankBox = document.getElementById('bankTransferNotice');
    const onlineBox = document.getElementById('onlineGatewayNotice');

    if (bankBox) bankBox.style.display = selected === 'bank_transfer' ? 'block' : 'none';
    if (onlineBox) onlineBox.style.display = selected === 'online_gateway' ? 'block' : 'none';
}

function handleOrderSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const fullName = form.fullName.value.trim();
    const phone = form.phone.value.trim();
    const email = form.email.value.trim();
    const address = form.address.value.trim();
    const notes = form.notes.value.trim();
    const paymentMethod = form.paymentMethod.value;

    if (!fullName || !phone || !email || !address) {
        alert('Vui lòng điền đầy đủ các thông tin giao xe bắt buộc!');
        return;
    }

    const phoneRegex = /^0\d{9}$/;
    if (!phoneRegex.test(phone)) {
        alert('Số điện thoại phải gồm 10 chữ số hợp lệ bắt đầu bằng số 0!');
        return;
    }

    const cart = getCart();
    if (cart.length === 0) {
        alert('Giỏ hàng trống!');
        return;
    }

    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let discount = 0;
    if (checkoutVoucher && subtotal >= checkoutVoucher.minOrder) {
        discount = checkoutVoucher.type === 'percentage'
            ? Math.min(checkoutVoucher.maxDiscount || Infinity, (subtotal * checkoutVoucher.value) / 100)
            : checkoutVoucher.value;
    }
    const total = Math.max(0, subtotal - discount);

    const currentUser = getCurrentUser();
    const paymentStatus = (paymentMethod === 'online_gateway' || paymentMethod === 'bank_transfer') ? 'paid' : 'unpaid';

    const orderData = {
        userId: currentUser ? currentUser.id : null,
        customerName: fullName,
        customerPhone: phone,
        customerEmail: email,
        shippingAddress: address,
        notes: notes,
        items: [...cart],
        subtotal: subtotal,
        discountAmount: discount,
        voucherCode: checkoutVoucher ? checkoutVoucher.code : '',
        totalAmount: total,
        paymentMethod: paymentMethod,
        paymentStatus: paymentStatus,
        orderStatus: 'processing'
    };

    // Save order & decrement simulated inventory
    const createdOrder = addOrder(orderData);

    // Clear cart & drafts
    clearCart();
    sessionStorage.removeItem('esfast_checkout_draft');

    // Show Success Modal
    showOrderSuccessModal(createdOrder);
}

function showOrderSuccessModal(order) {
    const modal = document.getElementById('orderSuccessModal');
    if (!modal) {
        alert(`Chúc mừng! Đặt xe thành công. Mã đơn hàng của bạn là: ${order.orderCode}`);
        window.location.href = 'orders.html';
        return;
    }

    document.getElementById('successOrderCode').textContent = order.orderCode;
    document.getElementById('successTotalAmount').textContent = formatMoney(order.totalAmount);
    document.getElementById('successCustomerName').textContent = order.customerName;
    document.getElementById('successAddress').textContent = order.shippingAddress;
    document.getElementById('successPayment').textContent = order.paymentMethod === 'cod'
        ? 'Tiền mặt khi giao xe (COD)'
        : order.paymentMethod === 'bank_transfer'
            ? 'Chuyển khoản ngân hàng (Mô phỏng)'
            : 'Cổng VNPAY (Mô phỏng)';

    modal.style.display = 'flex';
}

document.addEventListener('DOMContentLoaded', () => {
    initCheckoutPage();
});
