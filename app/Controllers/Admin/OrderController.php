<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Order;
use App\Models\Payment;

class OrderController extends Controller
{
    public function index(Request $request): void
    {
        $orderModel = new Order();

        $page = (int)$request->get('page', 1);
        $search = trim((string)$request->get('q', ''));
        $status = trim((string)$request->get('status', ''));

        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(order_code LIKE :q OR customer_name LIKE :q OR customer_phone LIKE :q)";
            $params[':q'] = "%{$search}%";
        }

        if (!empty($status)) {
            $where[] = "order_status = :status";
            $params[':status'] = $status;
        }

        $whereClause = implode(' AND ', $where);
        $pagination = $orderModel->paginate($page, 10, $whereClause, $params, 'id DESC');

        $this->view('admin.orders.index', [
            'pageTitle'  => 'Quản Lý Đơn Hàng - ESFAST Admin',
            'pagination' => $pagination,
            'orders'     => $pagination['data'],
            'search'     => $search,
            'status'     => $status,
        ], 'admin');
    }

    public function show(Request $request, int $id): void
    {
        $orderModel = new Order();
        $order = $orderModel->findWithDetails($id);

        if (!$order) {
            Session::setFlash('error', 'Không tìm thấy đơn hàng.');
            $this->redirect('/admin/orders');
            return;
        }

        $this->view('admin.orders.show', [
            'pageTitle' => 'Chi Tiết Đơn Hàng #' . $order['order_code'] . ' - ESFAST Admin',
            'order'     => $order,
        ], 'admin');
    }

    public function updateStatus(Request $request, int $id): void
    {
        $newStatus = (string)$request->post('order_status');
        $validStatuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];

        if (!in_array($newStatus, $validStatuses, true)) {
            Session::setFlash('error', 'Trạng thái cập nhật không hợp lệ.');
            $this->redirect('/admin/orders/' . $id);
            return;
        }

        $orderModel = new Order();
        $order = $orderModel->find($id);

        if (!$order) {
            Session::setFlash('error', 'Không tìm thấy đơn hàng.');
            $this->redirect('/admin/orders');
            return;
        }

        // Nếu chuyển sang hủy đơn -> dùng cancelOrder để hoàn lại kho và voucher an toàn
        if ($newStatus === 'cancelled' && $order['order_status'] !== 'cancelled') {
            $reason = trim((string)$request->post('cancel_reason', 'Quản trị viên hủy đơn'));
            $res = $orderModel->cancelOrder($id, null, $reason);
            if ($res['success']) {
                Session::setFlash('success', 'Đơn hàng đã được hủy và tồn kho đã được hoàn lại thành công.');
            } else {
                Session::setFlash('error', $res['message']);
            }
            $this->redirect('/admin/orders/' . $id);
            return;
        }

        // Cập nhật trạng thái
        $updateData = ['order_status' => $newStatus];
        if ($newStatus === 'completed') {
            $updateData['payment_status'] = 'paid';
        }

        $orderModel->update($id, $updateData);
        Session::setFlash('success', 'Đã cập nhật trạng thái đơn hàng thành công!');
        $this->redirect('/admin/orders/' . $id);
    }

    public function updatePaymentStatus(Request $request, int $id): void
    {
        $newPayStatus = (string)$request->post('payment_status');
        $validStatuses = ['unpaid', 'paid', 'refunded'];

        if (!in_array($newPayStatus, $validStatuses, true)) {
            Session::setFlash('error', 'Trạng thái thanh toán không hợp lệ.');
            $this->redirect('/admin/orders/' . $id);
            return;
        }

        $orderModel = new Order();
        $orderModel->update($id, ['payment_status' => $newPayStatus]);

        // Cập nhật vào payments nếu có
        $paymentModel = new Payment();
        $payment = $paymentModel->findBy('order_id', $id);
        if ($payment) {
            $paySt = $newPayStatus === 'paid' ? 'success' : ($newPayStatus === 'refunded' ? 'failed' : 'pending');
            $paymentModel->update($payment['id'], ['status' => $paySt]);
        }

        Session::setFlash('success', 'Đã cập nhật trạng thái thanh toán!');
        $this->redirect('/admin/orders/' . $id);
    }
}
