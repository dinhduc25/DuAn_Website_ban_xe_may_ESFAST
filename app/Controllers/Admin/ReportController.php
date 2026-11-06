<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Core\Database;
use App\Core\Request;
use PDO;

class ReportController extends Controller
{
    public function index(Request $request): void
    {
        $db = Database::getInstance();

        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-d'));

        // Thống kê doanh thu theo ngày
        $stmt = $db->prepare(
            "SELECT DATE(created_at) AS sale_date, 
                    COUNT(id) AS order_count,
                    COALESCE(SUM(total_amount), 0) AS daily_revenue
             FROM `orders`
             WHERE order_status = 'completed'
               AND DATE(created_at) >= :start_date
               AND DATE(created_at) <= :end_date
             GROUP BY DATE(created_at)
             ORDER BY sale_date ASC"
        );
        $stmt->execute([':start_date' => $startDate, ':end_date' => $endDate]);
        $dailyStats = $stmt->fetchAll();

        // Top xe bán chạy nhất
        $stmtTop = $db->query(
            "SELECT p.id, p.name, p.sku, p.image, 
                    SUM(od.quantity) AS total_sold,
                    SUM(od.total_price) AS total_sales
             FROM `order_details` od
             JOIN `orders` o ON od.order_id = o.id
             JOIN `products` p ON od.product_id = p.id
             WHERE o.order_status = 'completed'
             GROUP BY p.id
             ORDER BY total_sold DESC
             LIMIT 5"
        );
        $topProducts = $stmtTop->fetchAll();

        // Tổng doanh thu trong khoảng thời gian lọc
        $totalPeriodRevenue = 0;
        $totalPeriodOrders = 0;
        foreach ($dailyStats as $row) {
            $totalPeriodRevenue += (float)$row['daily_revenue'];
            $totalPeriodOrders += (int)$row['order_count'];
        }

        $this->view('admin.reports.index', [
            'pageTitle'          => 'Báo Cáo & Thống Kê Bán Hàng - ESFAST Admin',
            'dailyStats'         => $dailyStats,
            'topProducts'        => $topProducts,
            'totalPeriodRevenue' => $totalPeriodRevenue,
            'totalPeriodOrders'  => $totalPeriodOrders,
            'startDate'          => $startDate,
            'endDate'            => $endDate,
        ], 'admin');
    }
}
