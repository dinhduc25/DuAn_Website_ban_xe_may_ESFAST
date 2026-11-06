<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index(Request $request): void
    {
        $voucherModel = new Voucher();
        $vouchers = $voucherModel->all('id DESC');

        $this->view('admin.vouchers.index', [
            'pageTitle' => 'Quản Lý Khuyến Mãi & Voucher - ESFAST Admin',
            'vouchers'  => $vouchers,
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $validator = $this->validate($request->all(), [
            'code'             => 'required|unique:vouchers,code',
            'name'             => 'required|min:3',
            'discount_type'    => 'required|in:fixed,percentage',
            'discount_value'   => 'required|numeric',
            'min_order_amount' => 'required|numeric',
            'start_date'       => 'required',
            'end_date'         => 'required',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Vui lòng kiểm tra lại thông tin mã voucher.');
            $this->redirect('/admin/vouchers');
            return;
        }

        $voucherModel = new Voucher();
        $voucherModel->create([
            'code'             => strtoupper(trim((string)$request->post('code'))),
            'name'             => trim((string)$request->post('name')),
            'discount_type'    => (string)$request->post('discount_type'),
            'discount_value'   => (float)$request->post('discount_value'),
            'max_discount'     => !empty($request->post('max_discount')) ? (float)$request->post('max_discount') : null,
            'min_order_amount' => (float)$request->post('min_order_amount', 0),
            'usage_limit'      => (int)$request->post('usage_limit', 100),
            'start_date'       => (string)$request->post('start_date') . ' 00:00:00',
            'end_date'         => (string)$request->post('end_date') . ' 23:59:59',
            'is_active'        => $request->post('is_active') ? 1 : 0,
        ]);

        Session::setFlash('success', 'Tạo mới mã giảm giá thành công.');
        $this->redirect('/admin/vouchers');
    }

    public function delete(Request $request, int $id): void
    {
        $voucherModel = new Voucher();
        $voucherModel->delete($id);
        Session::setFlash('success', 'Đã xóa mã voucher thành công.');
        $this->redirect('/admin/vouchers');
    }
}
