<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Core\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $orderModel = new Order();
        $stats = $orderModel->getDashboardStats();

        $this->view('admin.dashboard.index', [
            'pageTitle' => 'Admin Dashboard - Tổng Quan Hệ Thống ESFAST',
            'stats'     => $stats,
        ], 'admin');
    }
}
