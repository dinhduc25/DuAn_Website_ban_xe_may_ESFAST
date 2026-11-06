<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request): void
    {
        $categoryModel = new Category();
        $categories = $categoryModel->allWithCount();

        $this->view('admin.categories.index', [
            'pageTitle'  => 'Quản Lý Danh Mục Xe Điện - ESFAST Admin',
            'categories' => $categories,
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $validator = $this->validate($request->all(), [
            'name' => 'required|min:2|max:100',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Tên danh mục không được để trống.');
            $this->redirect('/admin/categories');
            return;
        }

        $name = trim((string)$request->post('name'));
        $slug = $this->slugify($name);

        $categoryModel = new Category();
        if ($categoryModel->findBy('slug', $slug)) {
            $slug .= '-' . rand(10, 99);
        }

        $categoryModel->create([
            'name'        => $name,
            'slug'        => $slug,
            'description' => trim((string)$request->post('description', '')),
            'sort_order'  => (int)$request->post('sort_order', 0),
            'is_active'   => $request->post('is_active') ? 1 : 0,
        ]);

        Session::setFlash('success', 'Đã thêm danh mục thành công.');
        $this->redirect('/admin/categories');
    }

    public function update(Request $request, int $id): void
    {
        $categoryModel = new Category();
        $cat = $categoryModel->find($id);

        if (!$cat) {
            Session::setFlash('error', 'Không tìm thấy danh mục.');
            $this->redirect('/admin/categories');
            return;
        }

        $name = trim((string)$request->post('name'));
        if (empty($name)) {
            Session::setFlash('error', 'Tên danh mục không được để trống.');
            $this->redirect('/admin/categories');
            return;
        }

        $categoryModel->update($id, [
            'name'        => $name,
            'description' => trim((string)$request->post('description', '')),
            'sort_order'  => (int)$request->post('sort_order', 0),
            'is_active'   => $request->post('is_active') ? 1 : 0,
        ]);

        Session::setFlash('success', 'Cập nhật danh mục thành công.');
        $this->redirect('/admin/categories');
    }

    public function delete(Request $request, int $id): void
    {
        $categoryModel = new Category();
        $count = $categoryModel->countProducts($id);

        if ($count > 0) {
            Session::setFlash('error', "Không thể xóa danh mục này vì đang có {$count} sản phẩm xe điện liên kết. Hãy chuyển hoặc xóa các sản phẩm trước.");
            $this->redirect('/admin/categories');
            return;
        }

        $categoryModel->delete($id);
        Session::setFlash('success', 'Đã xóa danh mục thành công.');
        $this->redirect('/admin/categories');
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $vietnamese = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
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
