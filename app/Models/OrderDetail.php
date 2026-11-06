<?php

namespace App\Models;

class OrderDetail extends Model
{
    protected string $table = 'order_details';

    public function getByOrder(int $orderId): array
    {
        $stmt = $this->db->prepare(
            "SELECT od.*, p.image AS product_image, p.slug AS product_slug 
             FROM `order_details` od
             LEFT JOIN `products` p ON od.product_id = p.id
             WHERE od.order_id = :oid
             ORDER BY od.id ASC"
        );
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetchAll();
    }
}
