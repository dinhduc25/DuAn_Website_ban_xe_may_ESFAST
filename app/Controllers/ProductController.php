<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request): void
    {
        $productModel = new Product();
        $categoryModel = new Category();
        $brandModel = new Brand();

        $page = (int)$request->get('page', 1);
        $filters = [
            'q'           => $request->get('q', ''),
            'category_id' => $request->get('category', ''),
            'brand_id'    => $request->get('brand', ''),
            'min_price'   => $request->get('min_price', ''),
            'max_price'   => $request->get('max_price', ''),
            'speed'       => $request->get('speed', ''),
            'distance'    => $request->get('distance', ''),
            'battery'     => $request->get('battery', ''),
            'sort'        => $request->get('sort', 'newest'),
        ];

        $pagination = $productModel->filterProducts($filters, $page, 9);
        $categories = $categoryModel->allActive();
        $brands = $brandModel->allActive();

        $this->view('products.index', [
            'pageTitle'  => 'Danh Sách Xe Máy Điện ESFAST - Công Nghệ Đỉnh Cao',
            'pagination' => $pagination,
            'products'   => $pagination['data'],
            'categories' => $categories,
            'brands'     => $brands,
            'filters'    => $filters,
        ]);
    }

    public function show(Request $request, string $slug): void
    {
        $productModel = new Product();
        $product = $productModel->getBySlug($slug);

        if (!$product) {
            http_response_code(404);
            $this->view('layouts.404', ['pageTitle' => '404 - Không tìm thấy sản phẩm'], '');
            return;
        }

        // Tăng lượt xem
        $productModel->incrementViewCount($product['id']);

        // Lấy sản phẩm tương tự
        $relatedProducts = $productModel->getRelated($product['id'], $product['category_id'], 4);

        $this->view('products.show', [
            'pageTitle'       => $product['name'] . ' | Xe Máy Điện ESFAST',
            'product'         => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
