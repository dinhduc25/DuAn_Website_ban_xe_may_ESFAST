<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Models\Order;
use App\Models\User;

class UserController extends Controller
{
    public function profile(Request $request): void
    {
        $userModel = new User();
        $user = $userModel->find(Auth::id());

        $this->view('account.profile', [
            'pageTitle' => 'Tài Khoản Của Tôi - ESFAST',
            'user'      => $user,
        ]);
    }

    public function updateProfile(Request $request): void
    {
        $userId = Auth::id();

        $validator = $this->validate($request->all(), [
            'full_name' => 'required|min:2|max:100',
            'email'     => "required|email|unique:users,email,{$userId}",
            'phone'     => 'required|phone',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Dữ liệu cập nhật không hợp lệ.');
            $this->redirect('/account');
            return;
        }

        $userModel = new User();
        $userModel->update($userId, [
            'full_name' => trim((string)$request->post('full_name')),
            'email'     => trim((string)$request->post('email')),
            'phone'     => trim((string)$request->post('phone')),
            'address'   => trim((string)$request->post('address', '')),
        ]);

        // Cập nhật lại session
        $updatedUser = $userModel->find($userId);
        Auth::login($updatedUser);

        Session::setFlash('success', 'Thông tin cá nhân đã được cập nhật thành công.');
        $this->redirect('/account');
    }

    public function changePassword(Request $request): void
    {
        $userId = Auth::id();

        $validator = $this->validate($request->all(), [
            'current_password' => 'required',
            'new_password'     => 'required|min:6',
            'confirm_password' => 'required|match:new_password',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Vui lòng kiểm tra lại thông tin mật khẩu mới.');
            $this->redirect('/account');
            return;
        }

        $userModel = new User();
        $user = $userModel->find($userId);

        if (!password_verify((string)$request->post('current_password'), $user['password'])) {
            Session::setFlash('error', 'Mật khẩu hiện tại không đúng.');
            $this->redirect('/account');
            return;
        }

        $userModel->updatePassword($userId, (string)$request->post('new_password'));
        Session::setFlash('success', 'Đổi mật khẩu thành công!');
        $this->redirect('/account');
    }

    public function orders(Request $request): void
    {
        $orderModel = new Order();
        $page = (int)$request->get('page', 1);
        $pagination = $orderModel->getUserOrders(Auth::id(), $page, 5);

        $this->view('account.orders', [
            'pageTitle'  => 'Lịch Sử Mua Hàng - ESFAST',
            'pagination' => $pagination,
            'orders'     => $pagination['data'],
        ]);
    }

    public function orderDetail(Request $request, string $code): void
    {
        $orderModel = new Order();
        $order = $orderModel->getByCode($code);

        if (!$order || $order['user_id'] != Auth::id()) {
            Session::setFlash('error', 'Không tìm thấy đơn hàng hoặc bạn không có quyền xem đơn này.');
            $this->redirect('/account/orders');
            return;
        }

        $fullOrder = $orderModel->findWithDetails($order['id']);

        $this->view('account.order_detail', [
            'pageTitle' => 'Chi Tiết Đơn Hàng #' . $order['order_code'] . ' - ESFAST',
            'order'     => $fullOrder,
        ]);
    }

    public function cancelOrder(Request $request, int $orderId): void
    {
        $orderModel = new Order();
        $result = $orderModel->cancelOrder($orderId, Auth::id(), 'Khách hàng yêu cầu hủy từ trang quản lý cá nhân');

        if ($result['success']) {
            Session::setFlash('success', $result['message']);
        } else {
            Session::setFlash('error', $result['message']);
        }

        $this->redirect('/account/orders');
    }
}
