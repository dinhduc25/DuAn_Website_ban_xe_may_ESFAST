<?php

namespace App\Models;

class ProductVariant extends Model
{
    protected string $table = 'product_variants';

    public function getByProduct(int $productId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `product_variants` WHERE `product_id` = :pid ORDER BY `id` ASC"
        );
        $stmt->execute([':pid' => $productId]);
        return $stmt->fetchAll();
    }

    public function findWithProduct(int $variantId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT pv.*, p.name AS product_name, p.image AS product_image, p.price AS product_base_price
             FROM `product_variants` pv
             JOIN `products` p ON pv.product_id = p.id
             WHERE pv.id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $variantId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function decreaseStock(int $variantId, int $quantity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `product_variants` SET `stock_quantity` = GREATEST(0, `stock_quantity` - :qty) WHERE `id` = :id"
        );
        return $stmt->execute([':qty' => $quantity, ':id' => $variantId]);
    }

    public function increaseStock(int $variantId, int $quantity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `product_variants` SET `stock_quantity` = `stock_quantity` + :qty WHERE `id` = :id"
        );
        return $stmt->execute([':qty' => $quantity, ':id' => $variantId]);
    }
}
