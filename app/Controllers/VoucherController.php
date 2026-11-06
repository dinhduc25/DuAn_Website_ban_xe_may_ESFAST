<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Cart;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index(Request $request): void
    {
        $voucherModel = new Voucher();
        $vouchers = $voucherModel->getActiveVouchers();

        $this->view('vouchers.index', [
            'pageTitle' => 'Kho Ưu Đãi & Mã Giảm Giá Xe Máy Điện ESFAST',
            'vouchers'  => $vouchers,
        ]);
    }

    public function check(Request $request): void
    {
        $code = trim((string)$request->post('code'));
        $amount = (float)$request->post('amount', Cart::subtotal());

        $voucherModel = new Voucher();
        $res = $voucherModel->validateCode($code, $amount);

        $this->json($res);
    }
}
