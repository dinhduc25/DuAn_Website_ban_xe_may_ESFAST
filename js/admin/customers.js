/**
 * ESFAST ADMIN - CUSTOMERS MANAGEMENT (VANILLA JS)
 * View accounts, lock/unlock customer accounts, order history
 */

function initAdminCustomers() {
    const adminUser = requireAdminAuth('../login.html');
    if (!adminUser) return;

    renderCustomersTable();
}

function renderCustomersTable() {
    const users = getUsers();
    const search = document.getElementById('adminCustomerSearch')?.value.trim().toLowerCase() || '';
    const orders = getOrders();

    const filtered = users.filter(u => {
        if (search) {
            const matchUser = u.username.toLowerCase().includes(search);
            const matchEmail = u.email.toLowerCase().includes(search);
            const matchName = (u.fullName || '').toLowerCase().includes(search);
            const matchPhone = (u.phone || '').includes(search);
            if (!matchUser && !matchEmail && !matchName && !matchPhone) return false;
        }
        return true;
    });

    const tbody = document.getElementById('adminCustomersTableBody');
    if (!tbody) return;

    tbody.innerHTML = filtered.map(u => {
        const userOrders = orders.filter(o => o.userId === u.id || o.customerEmail === u.email);
        const isLocked = u.status === 'locked';
        const isAdmin = u.role === 'admin';

        return `
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 12px 16px; font-weight: 700; color: #0052cc;">#${u.id}</td>
                <td style="padding: 12px 16px;">
                    <div style="font-weight: 700; color: #0f172a;">${u.fullName || u.username}</div>
                    <div style="font-size: 0.8rem; color: #64748b;">@${u.username}</div>
                </td>
                <td style="padding: 12px 16px; font-size: 0.88rem; color: #334155;">
                    <div>${u.email}</div>
                    <div style="font-size: 0.8rem; color: #64748b;">${u.phone || 'Chưa cập nhật'}</div>
                </td>
                <td style="padding: 12px 16px;">
                    <span style="background: ${isAdmin ? '#eff6ff' : '#f8fafc'}; color: ${isAdmin ? '#0052cc' : '#475569'}; border: 1px solid ${isAdmin ? '#bfdbfe' : '#e2e8f0'}; padding: 3px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700;">
                        ${isAdmin ? '<i class="fas fa-shield-alt"></i> Quản trị viên' : '<i class="fas fa-user"></i> Khách hàng'}
                    </span>
                </td>
                <td style="padding: 12px 16px; font-weight: 700; color: #0f172a;">
                    ${userOrders.length} đơn
                </td>
                <td style="padding: 12px 16px;">
                    <span style="background: ${isLocked ? '#fef2f2' : '#ecfdf5'}; color: ${isLocked ? '#b91c1c' : '#047857'}; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700;">
                        ${isLocked ? 'Đã bị khóa' : 'Hoạt động'}
                    </span>
                </td>
                <td style="padding: 12px 16px; text-align: right;">
                    ${!isAdmin ? `
                        <button type="button" onclick="toggleLockCustomer(${u.id})" style="background: ${isLocked ? '#ecfdf5' : '#fee2e2'}; border: 1px solid ${isLocked ? '#a7f3d0' : '#fca5a5'}; color: ${isLocked ? '#047857' : '#ef4444'}; padding: 6px 14px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                            ${isLocked ? '<i class="fas fa-unlock"></i> Mở khóa' : '<i class="fas fa-lock"></i> Khóa tài khoản'}
                        </button>
                    ` : '<span style="font-size: 0.8rem; color: #94a3b8;">Không áp dụng</span>'}
                </td>
            </tr>
        `;
    }).join('');
}

function toggleLockCustomer(userId) {
    const users = getUsers();
    const user = users.find(u => u.id === Number(userId));
    if (!user) return;

    if (user.role === 'admin') {
        alert('Không thể khóa tài khoản Quản Trị Viên!');
        return;
    }

    user.status = user.status === 'locked' ? 'active' : 'locked';
    saveUsers(users);
    showToast(`Đã ${user.status === 'locked' ? 'khóa' : 'mở khóa'} tài khoản @${user.username}!`, 'info');
    renderCustomersTable();
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminCustomers();
});
