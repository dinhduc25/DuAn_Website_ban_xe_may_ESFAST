/**
 * ESFAST ECOMMERCE - AUTHENTICATION & ROUTE GUARD (VANILLA JS)
 * Handles Login, Register, Demo Quick-fill, Logout, and Simulated Route Protection
 */

// Route Guard Checkers
function requireAuth(redirectUrl = 'login.html') {
    const user = getCurrentUser();
    if (!user) {
        alert('Vui lòng đăng nhập để tiếp tục truy cập trang này!');
        window.location.href = redirectUrl;
        return null;
    }
    return user;
}

function requireAdminAuth(redirectUrl = '../login.html') {
    const user = getCurrentUser();
    if (!user) {
        alert('Vui lòng đăng nhập với tài khoản Quản Trị Viên!');
        window.location.href = redirectUrl;
        return null;
    }
    if (user.role !== 'admin') {
        alert('Bạn không có quyền truy cập khu vực Quản Trị!');
        window.location.href = '../index.html';
        return null;
    }
    return user;
}

// User Navigation & Header Status
function renderHeaderAuth() {
    const user = getCurrentUser();
    const authContainer = document.getElementById('headerAuthContainer');
    if (!authContainer) return;

    if (user) {
        const isAdmin = user.role === 'admin';
        authContainer.innerHTML = `
            <div class="user-dropdown-wrap" style="position: relative; display: inline-block;">
                <button class="user-menu-btn" id="userMenuToggleBtn" style="display: flex; align-items: center; gap: 8px; background: rgba(0,82,204,0.08); border: 1px solid rgba(0,82,204,0.2); padding: 7px 14px; border-radius: 999px; cursor: pointer; color: var(--primary); font-weight: 600;">
                    <i class="fas fa-user-circle" style="font-size: 1.2rem;"></i>
                    <span>${user.fullName || user.username}</span>
                    <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                </button>
                <div class="user-dropdown-menu" id="userDropdownMenu" style="display: none; position: absolute; right: 0; top: 115%; background: #ffffff; min-width: 210px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); border: 1px solid #e2e8f0; z-index: 1000; overflow: hidden;">
                    <div style="padding: 12px 16px; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                        <div style="font-size: 0.85rem; color: #64748b;">Đang đăng nhập:</div>
                        <strong style="color: #0f172a; font-size: 0.95rem;">${user.fullName}</strong>
                        <div style="font-size: 0.75rem; color: #0052cc; text-transform: uppercase; font-weight: 700; margin-top: 2px;">${user.role === 'admin' ? 'Quản Trị Viên' : 'Khách Hàng'}</div>
                    </div>
                    ${isAdmin ? `
                    <a href="admin/index.html" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; color: #0052cc; font-weight: 600; text-decoration: none; border-bottom: 1px solid #f1f5f9;">
                        <i class="fas fa-shield-alt"></i> Trang Quản Trị
                    </a>` : ''}
                    <a href="account.html" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; color: #334155; text-decoration: none;">
                        <i class="fas fa-user-cog"></i> Tài khoản của tôi
                    </a>
                    <a href="orders.html" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; color: #334155; text-decoration: none;">
                        <i class="fas fa-box"></i> Đơn mua xe
                    </a>
                    <a href="javascript:void(0)" id="headerLogoutBtn" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; color: #ef4444; border-top: 1px solid #f1f5f9; text-decoration: none;">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </div>
            </div>
        `;

        const toggleBtn = document.getElementById('userMenuToggleBtn');
        const menu = document.getElementById('userDropdownMenu');
        if (toggleBtn && menu) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            });
            document.addEventListener('click', () => {
                menu.style.display = 'none';
            });
        }

        const logoutBtn = document.getElementById('headerLogoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => {
                if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                    logoutUser();
                    alert('Đã đăng xuất thành công!');
                    window.location.href = 'index.html';
                }
            });
        }
    } else {
        authContainer.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <a href="login.html" class="btn btn-outline" style="padding: 8px 18px; font-size: 0.9rem; border-radius: 999px;">
                    <i class="fas fa-user"></i> Đăng nhập
                </a>
                <a href="register.html" class="btn btn-primary" style="padding: 8px 18px; font-size: 0.9rem; border-radius: 999px;">
                    Đăng ký
                </a>
            </div>
        `;
    }
}

// Quick-fill Demo Accounts
function fillDemoAccount(role) {
    const usernameInput = document.getElementById('loginUsername');
    const passwordInput = document.getElementById('loginPassword');

    if (!usernameInput || !passwordInput) return;

    if (role === 'customer') {
        usernameInput.value = 'customer';
        passwordInput.value = '123456';
    } else if (role === 'admin') {
        usernameInput.value = 'admin';
        passwordInput.value = 'admin123';
    }

    showToast(`Đã tự động điền tài khoản Demo: ${role.toUpperCase()}`, 'info');
}

// Toggle Password Visibility
function togglePasswordVisibility(inputId, toggleBtn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        if (toggleBtn) toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        input.type = 'password';
        if (toggleBtn) toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

// Register Form Handler
function handleRegister(e) {
    e.preventDefault();
    const form = e.target;
    const fullName = form.fullName.value.trim();
    const username = form.username.value.trim().toLowerCase();
    const email = form.email.value.trim().toLowerCase();
    const phone = form.phone.value.trim();
    const password = form.password.value;
    const confirmPassword = form.confirmPassword.value;
    const terms = form.terms && form.terms.checked;

    const errorBox = document.getElementById('registerError');
    if (errorBox) errorBox.style.display = 'none';

    // Validation
    if (!fullName || !username || !email || !phone || !password) {
        showError('Vui lòng điền đầy đủ tất cả các trường thông tin!');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showError('Định dạng email không hợp lệ!');
        return;
    }

    const phoneRegex = /^0\d{9}$/;
    if (!phoneRegex.test(phone)) {
        showError('Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0!');
        return;
    }

    if (password.length < 6) {
        showError('Mật khẩu phải có độ dài tối thiểu 6 ký tự!');
        return;
    }

    if (password !== confirmPassword) {
        showError('Mật khẩu xác nhận không trùng khớp!');
        return;
    }

    if (form.terms && !terms) {
        showError('Bạn cần đồng ý với Điều khoản dịch vụ và Chính sách bảo mật!');
        return;
    }

    const users = getUsers();

    // Check duplicate
    if (users.some(u => u.username === username)) {
        showError('Tên đăng nhập này đã được sử dụng!');
        return;
    }

    if (users.some(u => u.email === email)) {
        showError('Địa chỉ email này đã được đăng ký tài khoản!');
        return;
    }

    if (users.some(u => u.phone === phone)) {
        showError('Số điện thoại này đã được đăng ký tài khoản!');
        return;
    }

    const newUser = {
        id: users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1,
        username: username,
        email: email,
        fullName: fullName,
        phone: phone,
        password: password,
        address: 'Chưa cập nhật địa chỉ',
        role: 'customer',
        status: 'active',
        createdAt: new Date().toISOString().slice(0, 10)
    };

    users.push(newUser);
    saveUsers(users);

    alert(`Chúc mừng ${fullName}! Bạn đã đăng ký tài khoản thành công. Hệ thống sẽ chuyển hướng bạn đến trang Đăng nhập.`);
    window.location.href = 'login.html';

    function showError(msg) {
        if (errorBox) {
            errorBox.textContent = msg;
            errorBox.style.display = 'block';
        } else {
            alert(msg);
        }
    }
}

// Login Form Handler
function handleLogin(e) {
    e.preventDefault();
    const form = e.target;
    const account = form.account.value.trim().toLowerCase();
    const password = form.password.value;

    const errorBox = document.getElementById('loginError');
    if (errorBox) errorBox.style.display = 'none';

    if (!account || !password) {
        showError('Vui lòng nhập tài khoản/email và mật khẩu!');
        return;
    }

    const users = getUsers();
    const user = users.find(u => (u.username.toLowerCase() === account || u.email.toLowerCase() === account));

    if (!user || user.password !== password) {
        showError('Tài khoản hoặc mật khẩu không chính xác!');
        return;
    }

    if (user.status === 'locked') {
        showError('Tài khoản này đã bị khóa do vi phạm chính sách! Vui lòng liên hệ CSKH.');
        return;
    }

    // Save session
    setCurrentUser(user);

    alert(`Đăng nhập thành công! Chào mừng ${user.fullName}`);

    if (user.role === 'admin') {
        window.location.href = 'admin/index.html';
    } else {
        const urlParams = new URLSearchParams(window.location.search);
        const redirect = urlParams.get('redirect') || 'index.html';
        window.location.href = redirect;
    }

    function showError(msg) {
        if (errorBox) {
            errorBox.textContent = msg;
            errorBox.style.display = 'block';
        } else {
            alert(msg);
        }
    }
}

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    renderHeaderAuth();
});
