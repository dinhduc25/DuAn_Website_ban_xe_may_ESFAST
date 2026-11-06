<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Banner;
use App\Models\Contact;
use App\Models\Post;

class ContentController extends Controller
{
    public function banners(Request $request): void
    {
        $bannerModel = new Banner();
        $banners = $bannerModel->all('sort_order ASC, id DESC');

        $this->view('admin.content.banners', [
            'pageTitle' => 'Quản Lý Banner Quảng Cáo - ESFAST Admin',
            'banners'   => $banners,
        ], 'admin');
    }

    public function storeBanner(Request $request): void
    {
        $validator = $this->validate($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Tiêu đề banner không được để trống.');
            $this->redirect('/admin/content/banners');
            return;
        }

        $imageName = 'banner_default.jpg';
        $file = $request->file('image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $newName = 'banner_' . time() . '_' . rand(10, 99) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $uploadDir = dirname(__DIR__, 3) . '/public/assets/uploads/';
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                $imageName = $newName;
            }
        }

        $bannerModel = new Banner();
        $bannerModel->create([
            'title'      => trim((string)$request->post('title')),
            'subtitle'   => trim((string)$request->post('subtitle', '')),
            'image'      => $imageName,
            'link'       => trim((string)$request->post('link', '/products')),
            'badge'      => trim((string)$request->post('badge', 'KHUYẾN MÃI')),
            'sort_order' => (int)$request->post('sort_order', 0),
            'is_active'  => $request->post('is_active') ? 1 : 0,
        ]);

        Session::setFlash('success', 'Thêm banner mới thành công.');
        $this->redirect('/admin/content/banners');
    }

    public function deleteBanner(Request $request, int $id): void
    {
        $bannerModel = new Banner();
        $bannerModel->delete($id);
        Session::setFlash('success', 'Đã xóa banner thành công.');
        $this->redirect('/admin/content/banners');
    }

    public function posts(Request $request): void
    {
        $postModel = new Post();
        $posts = $postModel->all('id DESC');

        $this->view('admin.content.posts', [
            'pageTitle' => 'Quản Lý Tin Tức & Bài Viết - ESFAST Admin',
            'posts'     => $posts,
        ], 'admin');
    }

    public function storePost(Request $request): void
    {
        $validator = $this->validate($request->all(), [
            'title'   => 'required|min:5',
            'summary' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Vui lòng kiểm tra lại thông tin bài viết.');
            $this->redirect('/admin/content/posts');
            return;
        }

        $title = trim((string)$request->post('title'));
        $slug = $this->slugify($title);
        $imageName = 'post_default.jpg';

        $file = $request->file('image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $newName = 'post_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $uploadDir = dirname(__DIR__, 3) . '/public/assets/uploads/';
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                $imageName = $newName;
            }
        }

        $postModel = new Post();
        $postModel->create([
            'title'     => $title,
            'slug'      => $slug,
            'summary'   => trim((string)$request->post('summary')),
            'content'   => trim((string)$request->post('content')),
            'image'     => $imageName,
            'category'  => trim((string)$request->post('category', 'Tin tức xe điện')),
            'is_active' => $request->post('is_active') ? 1 : 0,
        ]);

        Session::setFlash('success', 'Đã đăng bài viết mới thành công.');
        $this->redirect('/admin/content/posts');
    }

    public function deletePost(Request $request, int $id): void
    {
        $postModel = new Post();
        $postModel->delete($id);
        Session::setFlash('success', 'Đã xóa bài viết thành công.');
        $this->redirect('/admin/content/posts');
    }

    public function contacts(Request $request): void
    {
        $contactModel = new Contact();
        $contacts = $contactModel->all('id DESC');

        $this->view('admin.content.contacts', [
            'pageTitle' => 'Khách Hàng Yêu Cầu Tư Vấn - ESFAST Admin',
            'contacts'  => $contacts,
        ], 'admin');
    }

    public function updateContactStatus(Request $request, int $id): void
    {
        $status = (string)$request->post('status', 'resolved');
        $contactModel = new Contact();
        $contactModel->update($id, ['status' => $status]);
        Session::setFlash('success', 'Đã cập nhật trạng thái liên hệ.');
        $this->redirect('/admin/content/contacts');
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $vietnamese = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        ];
        foreach ($vietnamese as $replacement => $pattern) {
            $text = preg_replace("/($pattern)/i", $replacement, $text);
        }
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
}
