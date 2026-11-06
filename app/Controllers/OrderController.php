<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Voucher;

class OrderController extends Controller
{
    public function checkout(Request $request): void
    {
        $items = Cart::items();
        if (empty($items)) {
            Session::setFlash('warning', 'Giỏ hàng của bạn đang trống. Vui lòng chọn xe trước khi thanh toán.');
            $this->redirect('/products');
            return;
        }

        $user = Auth::user();
        $subtotal = Cart::subtotal();
        $voucher = Cart::getVoucher();
        $discount = Cart::discountAmount();
        $total = Cart::total();

        $this->view('checkout.index', [
            'pageTitle' => 'Thanh Toán Đơn Hàng - ESFAST',
            'items'     => $items,
            'subtotal'  => $subtotal,
            'voucher'   => $voucher,
            'discount'  => $discount,
            'total'     => $total,
            'user'      => $user,
        ]);
    }

    public function processCheckout(Request $request): void
    {
        $items = Cart::items();
        if (empty($items)) {
            Session::setFlash('error', 'Giỏ hàng trống!');
            $this->redirect('/products');
            return;
        }

        $validator = $this->validate($request->all(), [
            'customer_name'    => 'required|min:2|max:100',
            'customer_phone'   => 'required|phone',
            'customer_email'   => 'required|email',
            'shipping_address' => 'required|min:5',
            'payment_method'   => 'required|in:cod,bank_transfer,online_gateway',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Vui lòng kiểm tra lại thông tin nhận hàng.');
            $this->view('checkout.index', [
                'pageTitle' => 'Thanh Toán Đơn Hàng - ESFAST',
                'items'     => $items,
                'subtotal'  => Cart::subtotal(),
                'voucher'   => Cart::getVoucher(),
                'discount'  => Cart::discountAmount(),
                'total'     => Cart::total(),
                'user'      => Auth::user(),
                'errors'    => $validator->errors(),
                'old'       => $request->all(),
            ]);
            return;
        }

        $orderData = [
            'user_id'          => Auth::id(),
            'customer_name'    => trim((string)$request->post('customer_name')),
            'customer_email'   => trim((string)$request->post('customer_email')),
            'customer_phone'   => trim((string)$request->post('customer_phone')),
            'shipping_address' => trim((string)$request->post('shipping_address')),
            'notes'            => trim((string)$request->post('notes', '')),
            'payment_method'   => (string)$request->post('payment_method'),
        ];

        $orderModel = new Order();
        $voucher = Cart::getVoucher();

        $result = $orderModel->createOrderWithDetails($orderData, $items, $voucher);

        if ($result['success']) {
            // Xóa sạch giỏ hàng và voucher sau khi đặt thành công
            Cart::clear();
            Session::setFlash('success', 'Đặt hàng thành công! ESFAST sẽ liên hệ với bạn trong thời gian sớm nhất.');
            $this->redirect('/checkout/success/' . $result['order_code']);
        } else {
            Session::setFlash('error', 'Không thể tạo đơn hàng: ' . $result['message']);
            $this->back();
        }
    }

    public function success(Request $request, string $code): void
    {
        $orderModel = new Order();
        $order = $orderModel->getByCode($code);

        if (!$order) {
            $this->redirect('/');
            return;
        }

        $fullOrder = $orderModel->findWithDetails($order['id']);

        $this->view('checkout.success', [
            'pageTitle' => 'Đặt Hàng Thành Công #' . $order['order_code'] . ' - ESFAST',
            'order'     => $fullOrder,
        ]);
    }
}
