<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        $bannerModel = new Banner();
        $productModel = new Product();
        $categoryModel = new Category();
        $brandModel = new Brand();
        $postModel = new Post();

        $banners = $bannerModel->getActiveBanners();
        $featuredProducts = $productModel->getFeatured(4);
        $bestSellers = $productModel->getBestSellers(4);
        $newArrivals = $productModel->getNewArrivals(4);
        $onSaleProducts = $productModel->getOnSale(4);
        $categories = $categoryModel->allActive();
        $brands = $brandModel->allActive();
        $latestPosts = $postModel->getLatest(3);

        $this->view('home.index', [
            'pageTitle'        => 'ESFAST - Xe Máy Điện Thông Minh Đẳng Cấp Việt Nam',
            'banners'          => $banners,
            'featuredProducts' => $featuredProducts,
            'bestSellers'      => $bestSellers,
            'newArrivals'      => $newArrivals,
            'onSaleProducts'   => $onSaleProducts,
            'categories'       => $categories,
            'brands'           => $brands,
            'latestPosts'      => $latestPosts,
        ]);
    }
}
