<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductController extends Controller
{
    public function index(Request $request): void
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $page = (int)$request->get('page', 1);
        $filters = [
            'q'           => $request->get('q', ''),
            'category_id' => $request->get('category', ''),
            'sort'        => $request->get('sort', 'newest'),
        ];

        $pagination = $productModel->filterProducts($filters, $page, 10);
        $categories = $categoryModel->allActive();

        $this->view('admin.products.index', [
            'pageTitle'  => 'Quản Lý Sản Phẩm Xe Máy Điện - ESFAST Admin',
            'pagination' => $pagination,
            'products'   => $pagination['data'],
            'categories' => $categories,
            'filters'    => $filters,
        ], 'admin');
    }

    public function create(Request $request): void
    {
        $categoryModel = new Category();
        $brandModel = new Brand();

        $this->view('admin.products.create', [
            'pageTitle'  => 'Thêm Mới Xe Máy Điện - ESFAST Admin',
            'categories' => $categoryModel->allActive(),
            'brands'     => $brandModel->allActive(),
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $validator = $this->validate($request->all(), [
            'name'           => 'required|min:3|max:200',
            'sku'            => 'required|unique:products,sku',
            'category_id'    => 'required|numeric',
            'price'          => 'required|numeric',
            'stock_quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Vui lòng kiểm tra lại thông tin sản phẩm.');
            $categoryModel = new Category();
            $brandModel = new Brand();
            $this->view('admin.products.create', [
                'pageTitle'  => 'Thêm Mới Xe Máy Điện - ESFAST Admin',
                'categories' => $categoryModel->allActive(),
                'brands'     => $brandModel->allActive(),
                'errors'     => $validator->errors(),
                'old'        => $request->all(),
            ], 'admin');
            return;
        }

        // Xử lý upload ảnh an toàn
        $imageName = 'default_bike.png';
        $file = $request->file('image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploaded = $this->handleImageUpload($file);
            if ($uploaded) {
                $imageName = $uploaded;
            }
        }

        $slug = $this->slugify($request->post('name'));
        // Đảm bảo slug duy nhất
        $productModel = new Product();
        if ($productModel->findBy('slug', $slug)) {
            $slug .= '-' . rand(100, 999);
        }

        $data = [
            'sku'              => strtoupper(trim((string)$request->post('sku'))),
            'name'             => trim((string)$request->post('name')),
            'slug'             => $slug,
            'category_id'      => (int)$request->post('category_id'),
            'brand_id'         => !empty($request->post('brand_id')) ? (int)$request->post('brand_id') : null,
            'price'            => (float)$request->post('price'),
            'sale_price'       => !empty($request->post('sale_price')) ? (float)$request->post('sale_price') : null,
            'image'            => $imageName,
            'description'      => trim((string)$request->post('description', '')),
            'content'          => trim((string)$request->post('content', '')),
            'max_speed'        => !empty($request->post('max_speed')) ? (int)$request->post('max_speed') : null,
            'max_distance'     => !empty($request->post('max_distance')) ? (int)$request->post('max_distance') : null,
            'motor_power'      => !empty($request->post('motor_power')) ? (int)$request->post('motor_power') : null,
            'battery_type'     => trim((string)$request->post('battery_type', '')),
            'battery_capacity' => trim((string)$request->post('battery_capacity', '')),
            'charging_time'    => trim((string)$request->post('charging_time', '')),
            'weight'           => !empty($request->post('weight')) ? (float)$request->post('weight') : null,
            'waterproof'       => trim((string)$request->post('waterproof', 'IP67')),
            'trunk_capacity'   => trim((string)$request->post('trunk_capacity', '')),
            'stock_quantity'   => (int)$request->post('stock_quantity', 0),
            'is_active'        => $request->post('is_active') ? 1 : 0,
            'is_featured'      => $request->post('is_featured') ? 1 : 0,
            'is_new'           => $request->post('is_new') ? 1 : 0,
        ];

        $productId = $productModel->create($data);

        Session::setFlash('success', 'Thêm mới xe máy điện thành công! Bạn có thể thêm phiên bản xe bên dưới.');
        $this->redirect('/admin/products/variants/' . $productId);
    }

    public function edit(Request $request, int $id): void
    {
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            Session::setFlash('error', 'Không tìm thấy sản phẩm.');
            $this->redirect('/admin/products');
            return;
        }

        $categoryModel = new Category();
        $brandModel = new Brand();

        $this->view('admin.products.edit', [
            'pageTitle'  => 'Chỉnh Sửa Xe: ' . $product['name'] . ' - ESFAST Admin',
            'product'    => $product,
            'categories' => $categoryModel->allActive(),
            'brands'     => $brandModel->allActive(),
        ], 'admin');
    }

    public function update(Request $request, int $id): void
    {
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            Session::setFlash('error', 'Không tìm thấy sản phẩm.');
            $this->redirect('/admin/products');
            return;
        }

        $validator = $this->validate($request->all(), [
            'name'           => 'required|min:3|max:200',
            'sku'            => "required|unique:products,sku,{$id}",
            'category_id'    => 'required|numeric',
            'price'          => 'required|numeric',
            'stock_quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Vui lòng kiểm tra lại thông tin sản phẩm.');
            $this->back();
            return;
        }

        $data = [
            'sku'              => strtoupper(trim((string)$request->post('sku'))),
            'name'             => trim((string)$request->post('name')),
            'category_id'      => (int)$request->post('category_id'),
            'brand_id'         => !empty($request->post('brand_id')) ? (int)$request->post('brand_id') : null,
            'price'            => (float)$request->post('price'),
            'sale_price'       => !empty($request->post('sale_price')) ? (float)$request->post('sale_price') : null,
            'description'      => trim((string)$request->post('description', '')),
            'content'          => trim((string)$request->post('content', '')),
            'max_speed'        => !empty($request->post('max_speed')) ? (int)$request->post('max_speed') : null,
            'max_distance'     => !empty($request->post('max_distance')) ? (int)$request->post('max_distance') : null,
            'motor_power'      => !empty($request->post('motor_power')) ? (int)$request->post('motor_power') : null,
            'battery_type'     => trim((string)$request->post('battery_type', '')),
            'battery_capacity' => trim((string)$request->post('battery_capacity', '')),
            'charging_time'    => trim((string)$request->post('charging_time', '')),
            'weight'           => !empty($request->post('weight')) ? (float)$request->post('weight') : null,
            'waterproof'       => trim((string)$request->post('waterproof', 'IP67')),
            'trunk_capacity'   => trim((string)$request->post('trunk_capacity', '')),
            'stock_quantity'   => (int)$request->post('stock_quantity', 0),
            'is_active'        => $request->post('is_active') ? 1 : 0,
            'is_featured'      => $request->post('is_featured') ? 1 : 0,
            'is_new'           => $request->post('is_new') ? 1 : 0,
        ];

        // Upload ảnh mới nếu có
        $file = $request->file('image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploaded = $this->handleImageUpload($file);
            if ($uploaded) {
                $data['image'] = $uploaded;
            }
        }

        $productModel->update($id, $data);

        Session::setFlash('success', 'Cập nhật thông tin xe thành công!');
        $this->redirect('/admin/products');
    }

    public function delete(Request $request, int $id): void
    {
        $productModel = new Product();
        $productModel->delete($id);
        Session::setFlash('success', 'Đã xóa sản phẩm thành công.');
        $this->redirect('/admin/products');
    }

    public function toggleStatus(Request $request, int $id): void
    {
        $productModel = new Product();
        $product = $productModel->find($id);
        if ($product) {
            $newStatus = $product['is_active'] == 1 ? 0 : 1;
            $productModel->update($id, ['is_active' => $newStatus]);
            Session::setFlash('success', 'Đã chuyển đổi trạng thái hiển thị của sản phẩm.');
        }
        $this->redirect('/admin/products');
    }

    public function variants(Request $request, int $id): void
    {
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            Session::setFlash('error', 'Không tìm thấy sản phẩm.');
            $this->redirect('/admin/products');
            return;
        }

        $variantModel = new ProductVariant();
        $variants = $variantModel->getByProduct($id);

        $this->view('admin.products.variants', [
            'pageTitle' => 'Quản Lý Biến Thể Xe: ' . $product['name'] . ' - ESFAST Admin',
            'product'   => $product,
            'variants'  => $variants,
        ], 'admin');
    }

    public function storeVariant(Request $request, int $id): void
    {
        $validator = $this->validate($request->all(), [
            'version_name'   => 'required',
            'color_name'     => 'required',
            'sku'            => 'required|unique:product_variants,sku',
            'price'          => 'required|numeric',
            'stock_quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            Session::setFlash('error', 'Dữ liệu biến thể không hợp lệ hoặc mã SKU đã tồn tại.');
            $this->redirect('/admin/products/variants/' . $id);
            return;
        }

        $variantModel = new ProductVariant();
        $variantModel->create([
            'product_id'     => $id,
            'sku'            => strtoupper(trim((string)$request->post('sku'))),
            'version_name'   => trim((string)$request->post('version_name')),
            'color_name'     => trim((string)$request->post('color_name')),
            'color_code'     => trim((string)$request->post('color_code', '#000000')),
            'battery_option' => trim((string)$request->post('battery_option', 'Kèm Pin LFP')),
            'price'          => (float)$request->post('price'),
            'stock_quantity' => (int)$request->post('stock_quantity', 0),
        ]);

        Session::setFlash('success', 'Thêm biến thể xe thành công!');
        $this->redirect('/admin/products/variants/' . $id);
    }

    public function deleteVariant(Request $request, int $variantId): void
    {
        $variantModel = new ProductVariant();
        $variant = $variantModel->find($variantId);

        if ($variant) {
            $productId = $variant['product_id'];
            $variantModel->delete($variantId);
            Session::setFlash('success', 'Đã xóa biến thể thành công.');
            $this->redirect('/admin/products/variants/' . $productId);
            return;
        }

        $this->redirect('/admin/products');
    }

    private function handleImageUpload(array $file): ?string
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes, true) || !in_array($ext, $allowedExts, true)) {
            return null;
        }

        // Tối đa 5MB
        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        $newName = 'bike_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $uploadDir = dirname(__DIR__, 3) . '/public/assets/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
            return $newName;
        }

        return null;
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        // Chuyển ký tự tiếng Việt có dấu
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
