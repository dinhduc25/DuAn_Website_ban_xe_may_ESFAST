<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Order extends Model
{
    protected string $table = 'orders';

    /**
     * Tạo đơn hàng hoàn chỉnh với Transaction đảm bảo an toàn tuyệt đối
     */
    public function createOrderWithDetails(array $orderData, array $cartItems, ?array $voucher = null): array
    {
        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            $productModel = new Product();
            $variantModel = new ProductVariant();

            $subtotal = 0.0;
            $verifiedItems = [];

            // 1. Kiểm tra tồn kho và lấy giá gốc từ Database
            foreach ($cartItems as $item) {
                $pid = (int)$item['product_id'];
                $vid = !empty($item['variant_id']) ? (int)$item['variant_id'] : null;
                $qty = (int)$item['quantity'];

                $product = $productModel->find($pid);
                if (!$product || $product['is_active'] != 1) {
                    throw new Exception("Sản phẩm \"{$item['name']}\" hiện không khả dụng.");
                }

                $price = $product['sale_price'] && $product['sale_price'] > 0 ? (float)$product['sale_price'] : (float)$product['price'];
                $variantInfo = null;

                if ($vid) {
                    $variant = $variantModel->find($vid);
                    if (!$variant || $variant['product_id'] != $pid) {
                        throw new Exception("Phiên bản xe không hợp lệ.");
                    }
                    if ($variant['stock_quantity'] < $qty) {
                        throw new Exception("Phiên bản \"{$variant['version_name']} - {$variant['color_name']}\" chỉ còn {$variant['stock_quantity']} xe trong kho.");
                    }
                    $price = (float)$variant['price'];
                    $variantInfo = $variant['version_name'] . ' - ' . $variant['color_name'];
                } else {
                    if ($product['stock_quantity'] < $qty) {
                        throw new Exception("Sản phẩm \"{$product['name']}\" chỉ còn {$product['stock_quantity']} xe trong kho.");
                    }
                }

                $itemTotal = $price * $qty;
                $subtotal += $itemTotal;

                $verifiedItems[] = [
                    'product_id'   => $pid,
                    'variant_id'   => $vid,
                    'product_name' => $product['name'],
                    'variant_info' => $variantInfo,
                    'price'        => $price,
                    'quantity'     => $qty,
                    'total_price'  => $itemTotal,
                ];
            }

            // 2. Tính toán giảm giá từ Server
            $discountAmount = 0.0;
            $voucherId = null;

            if ($voucher && !empty($voucher['id'])) {
                $voucherModel = new Voucher();
                $vCheck = $voucherModel->validateCode($voucher['code'], $subtotal);
                if ($vCheck['valid']) {
                    $voucherId = (int)$voucher['id'];
                    $discountAmount = (float)$vCheck['discount_amount'];
                }
            }

            $totalAmount = max(0.0, $subtotal - $discountAmount);

            // 3. Tạo mã đơn hàng độc nhất
            $orderCode = 'ESF-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            // 4. Lưu bảng orders
            $orderInsert = [
                'order_code'       => $orderCode,
                'user_id'          => $orderData['user_id'] ?? null,
                'customer_name'    => $orderData['customer_name'],
                'customer_email'   => $orderData['customer_email'],
                'customer_phone'   => $orderData['customer_phone'],
                'shipping_address' => $orderData['shipping_address'],
                'notes'            => $orderData['notes'] ?? null,
                'subtotal'         => $subtotal,
                'discount_amount'  => $discountAmount,
                'total_amount'     => $totalAmount,
                'voucher_id'       => $voucherId,
                'payment_method'   => $orderData['payment_method'] ?? 'cod',
                'payment_status'   => ($orderData['payment_method'] ?? 'cod') === 'online_gateway' ? 'paid' : 'unpaid',
                'order_status'     => 'pending',
            ];

            $orderId = $this->create($orderInsert);

            // 5. Lưu order_details và trừ tồn kho
            $detailModel = new OrderDetail();
            foreach ($verifiedItems as $item) {
                $item['order_id'] = $orderId;
                $detailModel->create($item);

                // Trừ tồn kho
                if ($item['variant_id']) {
                    $variantModel->decreaseStock($item['variant_id'], $item['quantity']);
                }
                $productModel->decreaseStock($item['product_id'], $item['quantity']);
            }

            // 6. Cập nhật voucher usage nếu có
            if ($voucherId) {
                $voucherModel = new Voucher();
                $voucherModel->incrementUsage($voucherId);

                $stmtVU = $db->prepare(
                    "INSERT INTO `voucher_usages` (`voucher_id`, `order_id`, `user_id`, `discount_amount`)
                     VALUES (:vid, :oid, :uid, :amt)"
                );
                $stmtVU->execute([
                    ':vid' => $voucherId,
                    ':oid' => $orderId,
                    ':uid' => $orderData['user_id'] ?? null,
                    ':amt' => $discountAmount,
                ]);
            }

            // 7. Tạo bản ghi payment
            $paymentMethod = $orderData['payment_method'] ?? 'cod';
            $stmtPay = $db->prepare(
                "INSERT INTO `payments` (`order_id`, `transaction_code`, `payment_method`, `amount`, `status`, `payment_response`)
                 VALUES (:oid, :tx, :pm, :amt, :st, :res)"
            );
            $txnCode = 'TXN-' . strtoupper($paymentMethod) . '-' . time() . '-' . rand(100, 999);
            $payStatus = $paymentMethod === 'online_gateway' ? 'success' : 'pending';
            $payResponse = $paymentMethod === 'online_gateway' 
                ? 'Thanh toán trực tuyến giả lập thành công qua Cổng ESFAST Pay'
                : 'Thanh toán khi nhận hàng (COD)';

            $stmtPay->execute([
                ':oid' => $orderId,
                ':tx'  => $txnCode,
                ':pm'  => $paymentMethod,
                ':amt' => $totalAmount,
                ':st'  => $payStatus,
                ':res' => $payResponse,
            ]);

            $db->commit();

            return [
                'success'    => true,
                'order_id'   => $orderId,
                'order_code' => $orderCode,
                'total'      => $totalAmount,
                'message'    => 'Đặt hàng thành công!'
            ];
        } catch (Exception $e) {
            $db->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getByCode(string $code): ?array
    {
        return $this->findBy('order_code', $code);
    }

    public function findWithDetails(int $id): ?array
    {
        $order = $this->find($id);
        if (!$order) return null;

        $detailModel = new OrderDetail();
        $order['details'] = $detailModel->getByOrder($id);

        $paymentModel = new Payment();
        $order['payment'] = $paymentModel->findBy('order_id', $id);

        return $order;
    }

    public function getUserOrders(int $userId, int $page = 1, int $perPage = 10): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM `orders` WHERE `user_id` = :uid");
        $countStmt->execute([':uid' => $userId]);
        $total = (int)$countStmt->fetchColumn();
        $totalPages = (int)ceil($total / $perPage);

        $stmt = $this->db->prepare(
            "SELECT * FROM `orders` WHERE `user_id` = :uid ORDER BY `id` DESC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll();

        // Nạp chi tiết cho từng đơn
        $detailModel = new OrderDetail();
        foreach ($orders as &$ord) {
            $ord['details'] = $detailModel->getByOrder($ord['id']);
        }

        return [
            'data'        => $orders,
            'total'       => $total,
            'per_page'    => $perPage,
            'page'        => $page,
            'total_pages' => $totalPages,
            'has_prev'    => $page > 1,
            'has_next'    => $page < $totalPages,
        ];
    }

    /**
     * Hủy đơn hàng và hoàn lại tồn kho
     */
    public function cancelOrder(int $orderId, ?int $userId = null, string $reason = 'Khách hàng yêu cầu hủy'): array
    {
        $order = $this->find($id = $orderId);
        if (!$order) {
            return ['success' => false, 'message' => 'Không tìm thấy đơn hàng.'];
        }

        if ($userId !== null && $order['user_id'] != $userId) {
            return ['success' => false, 'message' => 'Bạn không có quyền thao tác trên đơn hàng này.'];
        }

        if ($order['order_status'] !== 'pending') {
            return ['success' => false, 'message' => 'Chỉ có thể hủy đơn hàng khi đang ở trạng thái Chờ xử lý.'];
        }

        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            // Hoàn lại tồn kho
            $detailModel = new OrderDetail();
            $details = $detailModel->getByOrder($orderId);
            $productModel = new Product();
            $variantModel = new ProductVariant();

            foreach ($details as $d) {
                if (!empty($d['variant_id'])) {
                    $variantModel->increaseStock((int)$d['variant_id'], (int)$d['quantity']);
                }
                $productModel->increaseStock((int)$d['product_id'], (int)$d['quantity']);
            }

            // Hoàn voucher nếu có
            if (!empty($order['voucher_id'])) {
                $voucherModel = new Voucher();
                $voucherModel->decrementUsage((int)$order['voucher_id']);
            }

            // Cập nhật trạng thái
            $this->update($orderId, [
                'order_status'  => 'cancelled',
                'cancel_reason' => $reason,
            ]);

            $db->commit();
            return ['success' => true, 'message' => 'Hủy đơn hàng thành công.'];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Lỗi khi hủy đơn hàng: ' . $e->getMessage()];
        }
    }

    /**
     * Thống kê toàn diện cho Dashboard
     */
    public function getDashboardStats(): array
    {
        // 1. Tổng quan
        $totalProducts = (int)$this->db->query("SELECT COUNT(*) FROM `products` WHERE is_active = 1")->fetchColumn();
        $totalCategories = (int)$this->db->query("SELECT COUNT(*) FROM `categories` WHERE is_active = 1")->fetchColumn();
        $totalCustomers = (int)$this->db->query("SELECT COUNT(*) FROM `users` WHERE role = 'customer'")->fetchColumn();
        $totalOrders = (int)$this->db->query("SELECT COUNT(*) FROM `orders`")->fetchColumn();

        // 2. Tổng doanh thu (chỉ tính đơn hoàn thành)
        $totalRevenue = (float)$this->db->query(
            "SELECT COALESCE(SUM(total_amount), 0) FROM `orders` WHERE order_status = 'completed'"
        )->fetchColumn();

        // 3. Phân loại theo trạng thái đơn
        $pendingOrders = (int)$this->db->query("SELECT COUNT(*) FROM `orders` WHERE order_status = 'pending'")->fetchColumn();
        $processingOrders = (int)$this->db->query("SELECT COUNT(*) FROM `orders` WHERE order_status = 'processing'")->fetchColumn();
        $shippingOrders = (int)$this->db->query("SELECT COUNT(*) FROM `orders` WHERE order_status = 'shipping'")->fetchColumn();
        $completedOrders = (int)$this->db->query("SELECT COUNT(*) FROM `orders` WHERE order_status = 'completed'")->fetchColumn();
        $cancelledOrders = (int)$this->db->query("SELECT COUNT(*) FROM `orders` WHERE order_status = 'cancelled'")->fetchColumn();

        // 4. Doanh thu 6 tháng gần nhất
        $monthlyRevenue = $this->db->query(
            "SELECT DATE_FORMAT(created_at, '%m/%Y') AS month_label, 
                    COALESCE(SUM(total_amount), 0) AS total_revenue,
                    COUNT(id) AS order_count
             FROM `orders`
             WHERE order_status = 'completed'
             GROUP BY month_label
             ORDER BY MIN(created_at) ASC
             LIMIT 6"
        )->fetchAll();

        // 5. Đơn hàng mới nhất
        $recentOrders = $this->db->query(
            "SELECT * FROM `orders` ORDER BY id DESC LIMIT 5"
        )->fetchAll();

        return [
            'total_products'    => $totalProducts,
            'total_categories'  => $totalCategories,
            'total_customers'   => $totalCustomers,
            'total_orders'      => $totalOrders,
            'total_revenue'     => $totalRevenue,
            'pending_orders'    => $pendingOrders,
            'processing_orders' => $processingOrders,
            'shipping_orders'   => $shippingOrders,
            'completed_orders'  => $completedOrders,
            'cancelled_orders'  => $cancelledOrders,
            'monthly_revenue'   => $monthlyRevenue,
            'recent_orders'     => $recentOrders,
        ];
    }
}
