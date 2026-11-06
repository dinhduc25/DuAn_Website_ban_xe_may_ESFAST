<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request): void
    {
        $userModel = new User();
        $page = (int)$request->get('page', 1);
        $search = trim((string)$request->get('q', ''));

        $pagination = $userModel->getCustomers($page, 10, $search);

        $this->view('admin.users.index', [
            'pageTitle'  => 'Quản Lý Khách Hàng - ESFAST Admin',
            'pagination' => $pagination,
            'users'      => $pagination['data'],
            'search'     => $search,
        ], 'admin');
    }

    public function toggleStatus(Request $request, int $id): void
    {
        // Không cho phép tự khóa tài khoản của chính mình
        if ($id === Auth::id()) {
            Session::setFlash('error', 'Bạn không thể tự khóa tài khoản của chính mình.');
            $this->redirect('/admin/users');
            return;
        }

        $userModel = new User();
        $user = $userModel->find($id);

        if (!$user) {
            Session::setFlash('error', 'Không tìm thấy người dùng.');
            $this->redirect('/admin/users');
            return;
        }

        $newStatus = $user['status'] === 'active' ? 'locked' : 'active';
        $userModel->update($id, ['status' => $newStatus]);

        $actionText = $newStatus === 'locked' ? 'Khóa' : 'Mở khóa';
        Session::setFlash('success', "Đã {$actionText} tài khoản của {$user['full_name']} thành công.");
        $this->redirect('/admin/users');
    }

    public function admins(Request $request): void
    {
        $userModel = new User();
        $admins = $userModel->getAdmins();

        $this->view('admin.users.admins', [
            'pageTitle' => 'Quản Trị Viên Hệ Thống - ESFAST Admin',
            'admins'    => $admins,
        ], 'admin');
    }

    public function storeAdmin(Request $request): void
    {
        $validator = $this->validate($request->all(), [
            'full_name' => 'required|min:2|max:100',
            'username'  => 'required|min:4|max:50|unique:users,username',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Vui lòng kiểm tra lại thông tin quản trị viên.');
            $this->redirect('/admin/users/admins');
            return;
        }

        $userModel = new User();
        $userModel->create([
            'full_name' => trim((string)$request->post('full_name')),
            'username'  => trim((string)$request->post('username')),
            'email'     => trim((string)$request->post('email')),
            'phone'     => trim((string)$request->post('phone', '')),
            'password'  => password_hash((string)$request->post('password'), PASSWORD_BCRYPT),
            'role'      => 'admin',
            'status'    => 'active',
        ]);

        Session::setFlash('success', 'Đã thêm tài khoản quản trị mới thành công.');
        $this->redirect('/admin/users/admins');
    }
}
