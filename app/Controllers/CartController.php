<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Models\Cart;
use App\Models\Voucher;

class CartController extends Controller
{
    public function index(Request $request): void
    {
        $items = Cart::items();
        $subtotal = Cart::subtotal();
        $voucher = Cart::getVoucher();
        $discount = Cart::discountAmount();
        $total = Cart::total();

        $voucherModel = new Voucher();
        $activeVouchers = $voucherModel->getActiveVouchers();

        $this->view('cart.index', [
            'pageTitle'      => 'Giỏ Hàng Của Bạn - ESFAST',
            'items'          => $items,
            'subtotal'       => $subtotal,
            'voucher'        => $voucher,
            'discount'       => $discount,
            'total'          => $total,
            'activeVouchers' => $activeVouchers,
        ]);
    }

    public function add(Request $request): void
    {
        $productId = (int)$request->post('product_id');
        $variantId = $request->post('variant_id') ? (int)$request->post('variant_id') : null;
        $quantity = max(1, (int)$request->post('quantity', 1));
        $buyNow = (bool)$request->post('buy_now', false);

        $result = Cart::add($productId, $variantId, $quantity);

        if ($request->isAjax()) {
            $this->json([
                'success'    => $result['success'],
                'message'    => $result['message'],
                'cart_count' => Cart::count(),
                'subtotal'   => Cart::subtotal(),
                'total'      => Cart::total(),
            ]);
            return;
        }

        if ($result['success']) {
            Session::setFlash('success', $result['message']);
            if ($buyNow) {
                $this->redirect('/checkout');
            }
        } else {
            Session::setFlash('error', $result['message']);
        }

        $this->back();
    }

    public function update(Request $request): void
    {
        $key = (string)$request->post('key');
        $quantity = (int)$request->post('quantity', 1);

        $result = Cart::update($key, $quantity);

        if ($request->isAjax()) {
            $this->json([
                'success'    => $result['success'],
                'message'    => $result['message'],
                'cart_count' => Cart::count(),
                'subtotal'   => Cart::subtotal(),
                'discount'   => Cart::discountAmount(),
                'total'      => Cart::total(),
            ]);
            return;
        }

        if ($result['success']) {
            Session::setFlash('success', $result['message']);
        } else {
            Session::setFlash('error', $result['message']);
        }

        $this->redirect('/cart');
    }

    public function remove(Request $request): void
    {
        $key = (string)$request->post('key');
        $result = Cart::remove($key);

        if ($request->isAjax()) {
            $this->json([
                'success'    => true,
                'message'    => $result['message'],
                'cart_count' => Cart::count(),
                'subtotal'   => Cart::subtotal(),
                'discount'   => Cart::discountAmount(),
                'total'      => Cart::total(),
            ]);
            return;
        }

        Session::setFlash('success', $result['message']);
        $this->redirect('/cart');
    }

    public function clear(Request $request): void
    {
        Cart::clear();
        Session::setFlash('success', 'Đã xóa toàn bộ giỏ hàng.');
        $this->redirect('/cart');
    }

    public function applyVoucher(Request $request): void
    {
        $code = trim((string)$request->post('voucher_code'));
        if (empty($code)) {
            Session::setFlash('error', 'Vui lòng nhập mã giảm giá.');
            $this->redirect('/cart');
        }

        $subtotal = Cart::subtotal();
        if ($subtotal <= 0) {
            Session::setFlash('error', 'Giỏ hàng đang trống, không thể áp dụng mã giảm giá.');
            $this->redirect('/cart');
        }

        $voucherModel = new Voucher();
        $check = $voucherModel->validateCode($code, $subtotal);

        if ($check['valid']) {
            Cart::applyVoucher($check['voucher']);
            Session::setFlash('success', "Áp dụng thành công mã {$code}!");
        } else {
            Session::setFlash('error', $check['message']);
        }

        $this->redirect('/cart');
    }

    public function removeVoucher(Request $request): void
    {
        Cart::removeVoucher();
        Session::setFlash('success', 'Đã gỡ bỏ mã giảm giá.');
        $this->redirect('/cart');
    }

    public function miniCart(Request $request): void
    {
        $this->json([
            'count'    => Cart::count(),
            'subtotal' => Cart::subtotal(),
            'total'    => Cart::total(),
            'items'    => array_values(Cart::items()),
        ]);
    }
}
