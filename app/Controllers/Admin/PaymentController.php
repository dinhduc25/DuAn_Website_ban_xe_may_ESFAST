<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Order;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(Request $request): void
    {
        $paymentModel = new Payment();
        $page = (int)$request->get('page', 1);
        $status = (string)$request->get('status', '');

        $pagination = $paymentModel->allWithOrders($page, 10, $status);

        $this->view('admin.payments.index', [
            'pageTitle'  => 'Quản Lý Giao Dịch & Thanh Toán - ESFAST Admin',
            'pagination' => $pagination,
            'payments'   => $pagination['data'],
            'status'     => $status,
        ], 'admin');
    }

    public function confirm(Request $request, int $id): void
    {
        $paymentModel = new Payment();
        $payment = $paymentModel->find($id);

        if ($payment) {
            $paymentModel->update($id, [
                'status'           => 'success',
                'payment_response' => 'Quản trị viên đã đối soát và xác nhận thanh toán thành công.'
            ]);

            // Đồng bộ sang order
            $orderModel = new Order();
            $orderModel->update($payment['order_id'], ['payment_status' => 'paid']);

            Session::setFlash('success', 'Đã xác nhận giao dịch thành công!');
        }

        $this->redirect('/admin/payments');
    }
}
