<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Models\Contact;

class PageController extends Controller
{
    public function about(Request $request): void
    {
        $this->view('pages.about', [
            'pageTitle' => 'Về Chúng Tôi - ESFAST Tiên Phong Xe Máy Điện Thông Minh',
        ]);
    }

    public function contact(Request $request): void
    {
        $this->view('pages.contact', [
            'pageTitle' => 'Liên Hệ & Hỗ Trợ Khách Hàng - ESFAST',
        ]);
    }

    public function sendContact(Request $request): void
    {
        $validator = $this->validate($request->all(), [
            'full_name' => 'required|min:2|max:100',
            'email'     => 'required|email',
            'phone'     => 'required|phone',
            'subject'   => 'required|min:3',
            'message'   => 'required|min:10',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Vui lòng kiểm tra lại thông tin biểu mẫu.');
            $this->view('pages.contact', [
                'pageTitle' => 'Liên Hệ & Hỗ Trợ Khách Hàng - ESFAST',
                'errors'    => $validator->errors(),
                'old'       => $request->all(),
            ]);
            return;
        }

        $contactModel = new Contact();
        $contactModel->create([
            'full_name' => trim((string)$request->post('full_name')),
            'email'     => trim((string)$request->post('email')),
            'phone'     => trim((string)$request->post('phone')),
            'subject'   => trim((string)$request->post('subject')),
            'message'   => trim((string)$request->post('message')),
            'status'    => 'new',
        ]);

        Session::setFlash('success', 'Cảm ơn bạn đã liên hệ! Đội ngũ tư vấn ESFAST sẽ liên lạc lại trong vòng 24 giờ làm việc.');
        $this->redirect('/contact');
    }
}
