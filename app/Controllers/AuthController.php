<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                $this->redirect('/admin');
            } else {
                $this->redirect('/');
            }
            return;
        }

        $this->view('auth.login', [
            'pageTitle' => 'Đăng Nhập Tài Khoản - ESFAST',
        ], 'auth');
    }

    public function login(Request $request): void
    {
        $validator = $this->validate($request->all(), [
            'credential' => 'required',
            'password'   => 'required',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Vui lòng điền đầy đủ tên đăng nhập/email và mật khẩu.');
            $this->view('auth.login', [
                'pageTitle' => 'Đăng Nhập Tài Khoản - ESFAST',
                'errors'    => $validator->errors(),
                'old'       => $request->all(),
            ], 'auth');
            return;
        }

        $credential = trim((string)$request->post('credential'));
        $password = (string)$request->post('password');

        $userModel = new User();
        $user = $userModel->findByUsernameOrEmail($credential);

        if (!$user) {
            Session::setFlash('error', 'Tài khoản hoặc email không tồn tại trong hệ thống.');
            $this->view('auth.login', [
                'pageTitle' => 'Đăng Nhập Tài Khoản - ESFAST',
                'old'       => $request->all(),
            ], 'auth');
            return;
        }

        if ($user['status'] === 'locked') {
            Session::setFlash('error', 'Tài khoản của bạn đã bị tạm khóa. Vui lòng liên hệ hỗ trợ ESFAST.');
            $this->view('auth.login', [
                'pageTitle' => 'Đăng Nhập Tài Khoản - ESFAST',
                'old'       => $request->all(),
            ], 'auth');
            return;
        }

        if (!password_verify($password, $user['password'])) {
            Session::setFlash('error', 'Mật khẩu không chính xác. Vui lòng thử lại.');
            $this->view('auth.login', [
                'pageTitle' => 'Đăng Nhập Tài Khoản - ESFAST',
                'old'       => ['credential' => $credential],
            ], 'auth');
            return;
        }

        // Đăng nhập thành công
        Auth::login($user);
        Session::setFlash('success', "Xin chào mừng trở lại, {$user['full_name']}!");

        if ($user['role'] === 'admin') {
            $this->redirect('/admin');
        } else {
            $this->redirect('/');
        }
    }

    public function showRegister(Request $request): void
    {
        if (Auth::check()) {
            $this->redirect('/');
            return;
        }

        $this->view('auth.register', [
            'pageTitle' => 'Đăng Ký Tài Khoản Mới - ESFAST',
        ], 'auth');
    }

    public function register(Request $request): void
    {
        $validator = $this->validate($request->all(), [
            'full_name'        => 'required|min:2|max:100',
            'username'         => 'required|min:4|max:50|unique:users,username',
            'email'            => 'required|email|unique:users,email',
            'phone'            => 'required|phone',
            'password'         => 'required|min:6',
            'password_confirm' => 'required|match:password',
            'agree_terms'      => 'required',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Vui lòng kiểm tra lại thông tin đăng ký.');
            $this->view('auth.register', [
                'pageTitle' => 'Đăng Ký Tài Khoản Mới - ESFAST',
                'errors'    => $validator->errors(),
                'old'       => $request->all(),
            ], 'auth');
            return;
        }

        $userModel = new User();
        $userModel->register([
            'full_name' => trim((string)$request->post('full_name')),
            'username'  => trim((string)$request->post('username')),
            'email'     => trim((string)$request->post('email')),
            'phone'     => trim((string)$request->post('phone')),
            'password'  => (string)$request->post('password'),
            'address'   => trim((string)$request->post('address', '')),
        ]);

        Session::setFlash('success', 'Đăng ký tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.');
        $this->redirect('/login');
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        Session::setFlash('success', 'Bạn đã đăng xuất an toàn khỏi hệ thống.');
        $this->redirect('/');
    }
}
