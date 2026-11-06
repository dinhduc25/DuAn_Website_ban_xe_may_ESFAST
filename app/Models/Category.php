<?php

namespace App\Models;

class Category extends Model
{
    protected string $table = 'categories';

    public function allActive(): array
    {
        $stmt = $this->db->query(
            "SELECT c.*, COUNT(p.id) AS product_count 
             FROM `categories` c
             LEFT JOIN `products` p ON c.id = p.category_id AND p.is_active = 1
             WHERE c.is_active = 1
             GROUP BY c.id
             ORDER BY c.sort_order ASC, c.id ASC"
        );
        return $stmt->fetchAll();
    }

    public function allWithCount(): array
    {
        $stmt = $this->db->query(
            "SELECT c.*, COUNT(p.id) AS product_count 
             FROM `categories` c
             LEFT JOIN `products` p ON c.id = p.category_id
             GROUP BY c.id
             ORDER BY c.sort_order ASC, c.id DESC"
        );
        return $stmt->fetchAll();
    }

    public function getBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function countProducts(int $categoryId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM `products` WHERE `category_id` = :cid");
        $stmt->execute([':cid' => $categoryId]);
        return (int)$stmt->fetchColumn();
    }
}
