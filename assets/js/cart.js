/**
 * ESFAST ECOMMERCE - CART SCRIPT (VANILLA JS)
 * Handles Cart items, Quantity updates, Line item deletion, Voucher calculation
 */

let appliedVoucher = null;

function renderCartPage() {
    const cart = getCart();
    const cartContainer = document.getElementById('cartItemsContainer');
    const emptyState = document.getElementById('cartEmptyState');
    const contentLayout = document.getElementById('cartContentLayout');

    if (!cartContainer) return;

    if (cart.length === 0) {
        if (contentLayout) contentLayout.style.display = 'none';
        if (emptyState) emptyState.style.display = 'block';
        return;
    }

    if (contentLayout) contentLayout.style.display = 'grid';
    if (emptyState) emptyState.style.display = 'none';

    cartContainer.innerHTML = cart.map((item, index) => {
        const lineTotal = item.price * item.quantity;
        return `
            <div class="cart-item-row" style="display: grid; grid-template-columns: 100px 1fr auto auto; gap: 20px; align-items: center; padding: 20px 0; border-bottom: 1px solid #f1f5f9;">
                <!-- Image -->
                <div style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; padding: 8px; text-align: center;">
                    <img src="${item.image}" alt="${item.productName}" style="max-height: 70px; width: 100%; object-fit: contain;">
                </div>

                <!-- Info -->
                <div>
                    <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 4px;">
                        <a href="product-detail.html?id=${item.productId}" style="color: #0f172a; text-decoration: none;">${item.productName}</a>
                    </h4>
                    <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 6px;">
                        Phiên bản: <strong style="color: #0052cc;">${item.variantName || 'Tiêu chuẩn'}</strong>
                    </div>
                    <div style="font-size: 0.95rem; font-weight: 700; color: #0052cc;">
                        ${formatMoney(item.price)}
                    </div>
                </div>

                <!-- Qty Control -->
                <div style="display: flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden;">
                    <button type="button" onclick="handleUpdateCartQty(${index}, ${item.quantity - 1})" style="width: 32px; height: 32px; border: none; background: #f8fafc; cursor: pointer; color: #334155;">-</button>
                    <span style="width: 38px; text-align: center; font-weight: 700; font-size: 0.9rem;">${item.quantity}</span>
                    <button type="button" onclick="handleUpdateCartQty(${index}, ${item.quantity + 1})" style="width: 32px; height: 32px; border: none; background: #f8fafc; cursor: pointer; color: #334155;">+</button>
                </div>

                <!-- Line Total & Delete -->
                <div style="text-align: right; min-width: 140px;">
                    <div style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                        ${formatMoney(lineTotal)}
                    </div>
                    <button type="button" onclick="handleRemoveCartItem(${index})" style="background: none; border: none; color: #ef4444; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fas fa-trash-alt"></i> Xóa
                    </button>
                </div>
            </div>
        `;
    }).join('');

    calculateSummary();
}

function handleUpdateCartQty(index, newQty) {
    if (newQty <= 0) {
        if (confirm('Bạn có chắc chắn muốn bỏ sản phẩm này khỏi giỏ hàng?')) {
            removeCartItem(index);
        }
    } else {
        updateCartQuantity(index, newQty);
    }
    renderCartPage();
}

function handleRemoveCartItem(index) {
    if (confirm('Bạn có chắc chắn muốn bỏ sản phẩm này khỏi giỏ hàng?')) {
        removeCartItem(index);
        renderCartPage();
        showToast('Đã xóa mẫu xe khỏi giỏ hàng', 'info');
    }
}

function handleClearCart() {
    if (confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) {
        clearCart();
        renderCartPage();
        showToast('Đã làm trống giỏ hàng', 'info');
    }
}

function calculateSummary() {
    const cart = getCart();
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let discount = 0;

    if (appliedVoucher) {
        if (subtotal < appliedVoucher.minOrder) {
            alert(`Đơn hàng của bạn chưa đạt giá trị tối thiểu ${formatMoney(appliedVoucher.minOrder)} để áp dụng mã ${appliedVoucher.code}!`);
            appliedVoucher = null;
        } else {
            if (appliedVoucher.type === 'percentage') {
                discount = (subtotal * appliedVoucher.value) / 100;
                if (appliedVoucher.maxDiscount && discount > appliedVoucher.maxDiscount) {
                    discount = appliedVoucher.maxDiscount;
                }
            } else {
                discount = appliedVoucher.value;
            }
        }
    }

    const total = Math.max(0, subtotal - discount);

    const subtotalEl = document.getElementById('cartSubtotal');
    const discountEl = document.getElementById('cartDiscount');
    const totalEl = document.getElementById('cartTotal');
    const voucherTagEl = document.getElementById('appliedVoucherTag');

    if (subtotalEl) subtotalEl.textContent = formatMoney(subtotal);
    if (discountEl) discountEl.textContent = discount > 0 ? `-${formatMoney(discount)}` : '0 ₫';
    if (totalEl) totalEl.textContent = formatMoney(total);

    if (voucherTagEl) {
        if (appliedVoucher && discount > 0) {
            voucherTagEl.style.display = 'flex';
            voucherTagEl.innerHTML = `
                <span><i class="fas fa-tag"></i> Đã áp dụng mã: <strong>${appliedVoucher.code}</strong></span>
                <button type="button" onclick="removeAppliedVoucher()" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: 700;">&times;</button>
            `;
        } else {
            voucherTagEl.style.display = 'none';
        }
    }

    // Save active checkout draft
    sessionStorage.setItem('esfast_checkout_draft', JSON.stringify({
        subtotal,
        discount,
        voucherCode: appliedVoucher ? appliedVoucher.code : '',
        total
    }));
}

function applyVoucherCode(codeToApply = null) {
    const input = document.getElementById('voucherInput');
    const code = (codeToApply || (input ? input.value.trim() : '')).toUpperCase();

    if (!code) {
        alert('Vui lòng nhập mã giảm giá!');
        return;
    }

    const vouchers = getVouchers();
    const found = vouchers.find(v => v.code.toUpperCase() === code && v.isActive);

    if (!found) {
        alert('Mã giảm giá này không hợp lệ hoặc đã hết hạn sử dụng!');
        return;
    }

    const cart = getCart();
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    if (subtotal < found.minOrder) {
        alert(`Mã giảm giá ${found.code} chỉ áp dụng cho đơn hàng từ ${formatMoney(found.minOrder)} trở lên! (Hiện tại: ${formatMoney(subtotal)})`);
        return;
    }

    appliedVoucher = found;
    calculateSummary();
    if (input) input.value = '';
    showToast(`Áp dụng thành công mã voucher: ${found.code}`, 'success');
}

function removeAppliedVoucher() {
    appliedVoucher = null;
    calculateSummary();
    showToast('Đã hủy áp dụng mã giảm giá', 'info');
}

document.addEventListener('DOMContentLoaded', () => {
    renderCartPage();
});
