/**
 * ESFAST ADMIN - VOUCHERS MANAGEMENT (VANILLA JS)
 * Full CRUD for Promotional Coupons in LocalStorage
 */

let editingVoucherId = null;

function initAdminVouchers() {
    const adminUser = requireAdminAuth('../login.html');
    if (!adminUser) return;

    renderVouchersTable();
}

function renderVouchersTable() {
    const vouchers = getVouchers();
    const tbody = document.getElementById('adminVouchersTableBody');
    if (!tbody) return;

    tbody.innerHTML = vouchers.map(v => `
        <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 12px 16px;">
                <strong style="color: #0052cc; font-size: 1rem;">${v.code}</strong>
                <div style="font-size: 0.8rem; color: #64748b;">${v.name}</div>
            </td>
            <td style="padding: 12px 16px;">
                ${v.type === 'percentage' ? `<span style="font-weight: 700; color: #0f172a;">Giảm ${v.value}%</span>` : `<span style="font-weight: 700; color: #10b981;">Giảm ${formatMoney(v.value)}</span>`}
            </td>
            <td style="padding: 12px 16px; font-size: 0.88rem; color: #334155;">
                Đơn từ <strong>${formatMoney(v.minOrder)}</strong>
            </td>
            <td style="padding: 12px 16px; font-size: 0.85rem; color: #64748b;">
                ${v.usedCount} / ${v.usageLimit} lượt
            </td>
            <td style="padding: 12px 16px;">
                <button type="button" onclick="toggleVoucherStatus(${v.id})" style="border: none; background: ${v.isActive ? '#ecfdf5' : '#fef2f2'}; color: ${v.isActive ? '#047857' : '#b91c1c'}; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; cursor: pointer;">
                    ${v.isActive ? 'Đang kích hoạt' : 'Tạm khóa'}
                </button>
            </td>
            <td style="padding: 12px 16px; text-align: right;">
                <button type="button" onclick="openEditVoucherModal(${v.id})" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #0052cc; padding: 5px 10px; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" onclick="deleteVoucher(${v.id})" style="background: #fee2e2; border: 1px solid #fca5a5; color: #ef4444; padding: 5px 10px; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function openAddVoucherModal() {
    editingVoucherId = null;
    document.getElementById('voucherModalTitle').textContent = 'Thêm Mới Mã Giảm Giá';
    document.getElementById('voucherForm').reset();
    document.getElementById('voucherModal').style.display = 'flex';
}

function openEditVoucherModal(id) {
    editingVoucherId = id;
    const vouchers = getVouchers();
    const v = vouchers.find(x => x.id === Number(id));
    if (!v) return;

    document.getElementById('voucherModalTitle').textContent = `Chỉnh Sửa Mã: ${v.code}`;
    const form = document.getElementById('voucherForm');
    form.code.value = v.code;
    form.name.value = v.name;
    form.type.value = v.type;
    form.value.value = v.value;
    form.minOrder.value = v.minOrder;
    form.usageLimit.value = v.usageLimit;
    document.getElementById('voucherModal').style.display = 'flex';
}

function closeVoucherModal() {
    document.getElementById('voucherModal').style.display = 'none';
}

function handleSaveVoucher(e) {
    e.preventDefault();
    const form = e.target;
    const vouchers = getVouchers();

    const voucherData = {
        code: form.code.value.trim().toUpperCase(),
        name: form.name.value.trim(),
        type: form.type.value,
        value: Number(form.value.value),
        minOrder: Number(form.minOrder.value),
        maxDiscount: form.type.value === 'percentage' ? 2000000 : null,
        usageLimit: Number(form.usageLimit.value)
    };

    if (editingVoucherId) {
        const idx = vouchers.findIndex(v => v.id === editingVoucherId);
        if (idx > -1) {
            vouchers[idx] = { ...vouchers[idx], ...voucherData };
            saveVouchers(vouchers);
            showToast('Cập nhật mã giảm giá thành công!', 'success');
        }
    } else {
        const newId = vouchers.length > 0 ? Math.max(...vouchers.map(v => v.id)) + 1 : 1;
        vouchers.unshift({
            id: newId,
            ...voucherData,
            usedCount: 0,
            startDate: '2026-01-01',
            endDate: '2026-12-31',
            isActive: true
        });
        saveVouchers(vouchers);
        showToast('Tạo mới mã giảm giá thành công!', 'success');
    }

    closeVoucherModal();
    renderVouchersTable();
}

function deleteVoucher(id) {
    if (confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')) {
        let vouchers = getVouchers();
        vouchers = vouchers.filter(v => v.id !== Number(id));
        saveVouchers(vouchers);
        showToast('Đã xóa mã voucher!', 'info');
        renderVouchersTable();
    }
}

function toggleVoucherStatus(id) {
    const vouchers = getVouchers();
    const v = vouchers.find(x => x.id === Number(id));
    if (v) {
        v.isActive = !v.isActive;
        saveVouchers(vouchers);
        renderVouchersTable();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminVouchers();
});
