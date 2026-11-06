<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug): void
    {
        $categoryModel = new Category();
        $category = $categoryModel->getBySlug($slug);

        if (!$category) {
            http_response_code(404);
            $this->view('layouts.404', ['pageTitle' => '404 - Không tìm thấy danh mục'], '');
            return;
        }

        $productModel = new Product();
        $brandModel = new Brand();

        $page = (int)$request->get('page', 1);
        $filters = [
            'q'           => $request->get('q', ''),
            'category_id' => $category['id'],
            'brand_id'    => $request->get('brand', ''),
            'min_price'   => $request->get('min_price', ''),
            'max_price'   => $request->get('max_price', ''),
            'speed'       => $request->get('speed', ''),
            'distance'    => $request->get('distance', ''),
            'battery'     => $request->get('battery', ''),
            'sort'        => $request->get('sort', 'newest'),
        ];

        $pagination = $productModel->filterProducts($filters, $page, 9);
        $allCategories = $categoryModel->allActive();
        $brands = $brandModel->allActive();

        $this->view('categories.show', [
            'pageTitle'     => $category['name'] . ' - Xe Máy Điện ESFAST',
            'category'      => $category,
            'pagination'    => $pagination,
            'products'      => $pagination['data'],
            'allCategories' => $allCategories,
            'brands'        => $brands,
            'filters'       => $filters,
        ]);
    }
}
