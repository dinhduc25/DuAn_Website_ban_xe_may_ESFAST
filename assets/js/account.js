/**
 * ESFAST ECOMMERCE - ACCOUNT MANAGEMENT SCRIPT (VANILLA JS)
 * Handles customer profile view & edit, password change, and security
 */

function initAccountPage() {
    const user = requireAuth('login.html?redirect=account.html');
    if (!user) return;

    // Populate profile fields
    const fullNameInput = document.getElementById('accFullName');
    const usernameInput = document.getElementById('accUsername');
    const emailInput = document.getElementById('accEmail');
    const phoneInput = document.getElementById('accPhone');
    const addressInput = document.getElementById('accAddress');
    const welcomeName = document.getElementById('accWelcomeName');
    const roleBadge = document.getElementById('accRoleBadge');

    if (fullNameInput) fullNameInput.value = user.fullName || '';
    if (usernameInput) usernameInput.value = user.username || '';
    if (emailInput) emailInput.value = user.email || '';
    if (phoneInput) phoneInput.value = user.phone || '';
    if (addressInput) addressInput.value = user.address && !user.address.includes('Chưa cập nhật') ? user.address : '';
    if (welcomeName) welcomeName.textContent = user.fullName || user.username;
    if (roleBadge) roleBadge.textContent = user.role === 'admin' ? 'Quản Trị Viên' : 'Thành Viên ESFAST';

    // Count user's orders
    const orders = getOrders().filter(o => o.userId === user.id || o.customerEmail === user.email || o.customerPhone === user.phone);
    const orderCountEl = document.getElementById('accOrderCount');
    if (orderCountEl) orderCountEl.textContent = `${orders.length} đơn`;
}

function handleUpdateProfile(e) {
    e.preventDefault();
    const user = getCurrentUser();
    if (!user) return;

    const form = e.target;
    const fullName = form.fullName.value.trim();
    const email = form.email.value.trim().toLowerCase();
    const phone = form.phone.value.trim();
    const address = form.address.value.trim();

    if (!fullName || !email || !phone) {
        alert('Họ tên, email và số điện thoại không được để trống!');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Email không hợp lệ!');
        return;
    }

    const phoneRegex = /^0\d{9}$/;
    if (!phoneRegex.test(phone)) {
        alert('Số điện thoại phải gồm 10 số hợp lệ!');
        return;
    }

    // Check duplicate email or phone with other users
    const users = getUsers();
    const emailDuplicate = users.some(u => u.id !== user.id && u.email === email);
    if (emailDuplicate) {
        alert('Email này đã được tài khoản khác sử dụng!');
        return;
    }

    const phoneDuplicate = users.some(u => u.id !== user.id && u.phone === phone);
    if (phoneDuplicate) {
        alert('Số điện thoại này đã được tài khoản khác sử dụng!');
        return;
    }

    // Update in database & session
    const idx = users.findIndex(u => u.id === user.id);
    if (idx > -1) {
        users[idx].fullName = fullName;
        users[idx].email = email;
        users[idx].phone = phone;
        users[idx].address = address;
        saveUsers(users);

        user.fullName = fullName;
        user.email = email;
        user.phone = phone;
        user.address = address;
        setCurrentUser(user);

        alert('Cập nhật thông tin tài khoản thành công!');
        window.location.reload();
    }
}

function handleChangePassword(e) {
    e.preventDefault();
    const user = getCurrentUser();
    if (!user) return;

    const form = e.target;
    const currentPass = form.currentPassword.value;
    const newPass = form.newPassword.value;
    const confirmNewPass = form.confirmNewPassword.value;

    if (user.password !== currentPass) {
        alert('Mật khẩu hiện tại không chính xác!');
        return;
    }

    if (newPass.length < 6) {
        alert('Mật khẩu mới phải có tối thiểu 6 ký tự!');
        return;
    }

    if (newPass !== confirmNewPass) {
        alert('Xác nhận mật khẩu mới không trùng khớp!');
        return;
    }

    const users = getUsers();
    const idx = users.findIndex(u => u.id === user.id);
    if (idx > -1) {
        users[idx].password = newPass;
        saveUsers(users);

        user.password = newPass;
        setCurrentUser(user);

        alert('Đổi mật khẩu thành công! Vui lòng ghi nhớ mật khẩu mới.');
        form.reset();
    }
}

function handleAccountLogout() {
    if (confirm('Bạn có chắc chắn muốn đăng xuất tài khoản?')) {
        logoutUser();
        alert('Đã đăng xuất thành công!');
        window.location.href = 'login.html';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initAccountPage();
});
