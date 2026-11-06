<?php

use App\Core\Router;

// ==========================================
// 1. CLIENT / PUBLIC ROUTES
// ==========================================
Router::get('/', 'HomeController@index');
Router::get('/products', 'ProductController@index');
Router::get('/products/{slug}', 'ProductController@show');
Router::get('/categories/{slug}', 'CategoryController@show');

// Cart
Router::get('/cart', 'CartController@index');
Router::post('/cart/add', 'CartController@add', ['csrf']);
Router::post('/cart/update', 'CartController@update', ['csrf']);
Router::post('/cart/remove', 'CartController@remove', ['csrf']);
Router::post('/cart/clear', 'CartController@clear', ['csrf']);
Router::post('/cart/voucher/apply', 'CartController@applyVoucher', ['csrf']);
Router::post('/cart/voucher/remove', 'CartController@removeVoucher', ['csrf']);
Router::get('/cart/mini', 'CartController@miniCart');

// Checkout & Order Success
Router::get('/checkout', 'OrderController@checkout');
Router::post('/checkout', 'OrderController@processCheckout', ['csrf']);
Router::get('/checkout/success/{code}', 'OrderController@success');

// Vouchers & Promotions
Router::get('/vouchers', 'VoucherController@index');
Router::post('/vouchers/check', 'VoucherController@check');

// Static / Informational Pages
Router::get('/about', 'PageController@about');
Router::get('/contact', 'PageController@contact');
Router::post('/contact', 'PageController@sendContact', ['csrf']);

// ==========================================
// 2. AUTHENTICATION ROUTES
// ==========================================
Router::get('/login', 'AuthController@showLogin');
Router::post('/login', 'AuthController@login', ['csrf']);
Router::get('/register', 'AuthController@showRegister');
Router::post('/register', 'AuthController@register', ['csrf']);
Router::get('/logout', 'AuthController@logout');

// ==========================================
// 3. CUSTOMER ACCOUNT ROUTES (auth middleware)
// ==========================================
Router::get('/account', 'UserController@profile', ['auth']);
Router::post('/account/profile', 'UserController@updateProfile', ['auth', 'csrf']);
Router::post('/account/password', 'UserController@changePassword', ['auth', 'csrf']);
Router::get('/account/orders', 'UserController@orders', ['auth']);
Router::get('/account/orders/{code}', 'UserController@orderDetail', ['auth']);
Router::post('/account/orders/cancel/{id}', 'UserController@cancelOrder', ['auth', 'csrf']);

// ==========================================
// 4. ADMIN ROUTES (admin middleware)
// ==========================================
// Dashboard
Router::get('/admin', 'Admin\DashboardController@index', ['admin']);
Router::get('/admin/dashboard', 'Admin\DashboardController@index', ['admin']);

// Products & Variants
Router::get('/admin/products', 'Admin\ProductController@index', ['admin']);
Router::get('/admin/products/create', 'Admin\ProductController@create', ['admin']);
Router::post('/admin/products/store', 'Admin\ProductController@store', ['admin', 'csrf']);
Router::get('/admin/products/edit/{id}', 'Admin\ProductController@edit', ['admin']);
Router::post('/admin/products/update/{id}', 'Admin\ProductController@update', ['admin', 'csrf']);
Router::post('/admin/products/delete/{id}', 'Admin\ProductController@delete', ['admin', 'csrf']);
Router::post('/admin/products/toggle/{id}', 'Admin\ProductController@toggleStatus', ['admin', 'csrf']);
Router::get('/admin/products/variants/{id}', 'Admin\ProductController@variants', ['admin']);
Router::post('/admin/products/variants/{id}', 'Admin\ProductController@storeVariant', ['admin', 'csrf']);
Router::post('/admin/products/variants/delete/{id}', 'Admin\ProductController@deleteVariant', ['admin', 'csrf']);

// Categories
Router::get('/admin/categories', 'Admin\CategoryController@index', ['admin']);
Router::post('/admin/categories/store', 'Admin\CategoryController@store', ['admin', 'csrf']);
Router::post('/admin/categories/update/{id}', 'Admin\CategoryController@update', ['admin', 'csrf']);
Router::post('/admin/categories/delete/{id}', 'Admin\CategoryController@delete', ['admin', 'csrf']);

// Orders
Router::get('/admin/orders', 'Admin\OrderController@index', ['admin']);
Router::get('/admin/orders/{id}', 'Admin\OrderController@show', ['admin']);
Router::post('/admin/orders/{id}/status', 'Admin\OrderController@updateStatus', ['admin', 'csrf']);
Router::post('/admin/orders/{id}/payment', 'Admin\OrderController@updatePaymentStatus', ['admin', 'csrf']);

// Users / Customers & Admin Management
Router::get('/admin/users', 'Admin\UserController@index', ['admin']);
Router::post('/admin/users/toggle/{id}', 'Admin\UserController@toggleStatus', ['admin', 'csrf']);
Router::get('/admin/users/admins', 'Admin\UserController@admins', ['admin']);
Router::post('/admin/users/admins', 'Admin\UserController@storeAdmin', ['admin', 'csrf']);

// Vouchers
Router::get('/admin/vouchers', 'Admin\VoucherController@index', ['admin']);
Router::post('/admin/vouchers/store', 'Admin\VoucherController@store', ['admin', 'csrf']);
Router::post('/admin/vouchers/delete/{id}', 'Admin\VoucherController@delete', ['admin', 'csrf']);

// Payments & Transactions
Router::get('/admin/payments', 'Admin\PaymentController@index', ['admin']);
Router::post('/admin/payments/confirm/{id}', 'Admin\PaymentController@confirm', ['admin', 'csrf']);

// Website Content: Banners, Posts, Contacts
Router::get('/admin/content/banners', 'Admin\ContentController@banners', ['admin']);
Router::post('/admin/content/banners', 'Admin\ContentController@storeBanner', ['admin', 'csrf']);
Router::post('/admin/content/banners/delete/{id}', 'Admin\ContentController@deleteBanner', ['admin', 'csrf']);
Router::get('/admin/content/posts', 'Admin\ContentController@posts', ['admin']);
Router::post('/admin/content/posts', 'Admin\ContentController@storePost', ['admin', 'csrf']);
Router::post('/admin/content/posts/delete/{id}', 'Admin\ContentController@deletePost', ['admin', 'csrf']);
Router::get('/admin/content/contacts', 'Admin\ContentController@contacts', ['admin']);
Router::post('/admin/content/contacts/{id}/status', 'Admin\ContentController@updateContactStatus', ['admin', 'csrf']);

// Reports & Statistics
Router::get('/admin/reports', 'Admin\ReportController@index', ['admin']);
