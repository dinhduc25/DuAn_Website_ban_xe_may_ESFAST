<?php

namespace App\Models;

use PDO;

class Product extends Model
{
    protected string $table = 'products';

    /**
     * Lấy danh sách xe máy điện nổi bật
     */
    public function getFeatured(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name, b.name AS brand_name 
             FROM `products` p
             JOIN `categories` c ON p.category_id = c.id
             LEFT JOIN `brands` b ON p.brand_id = b.id
             WHERE p.is_active = 1 AND p.is_featured = 1
             ORDER BY p.id DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy danh sách xe bán chạy
     */
    public function getBestSellers(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name, b.name AS brand_name 
             FROM `products` p
             JOIN `categories` c ON p.category_id = c.id
             LEFT JOIN `brands` b ON p.brand_id = b.id
             WHERE p.is_active = 1
             ORDER BY p.view_count DESC, p.id DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy danh sách xe mới nhất
     */
    public function getNewArrivals(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name, b.name AS brand_name 
             FROM `products` p
             JOIN `categories` c ON p.category_id = c.id
             LEFT JOIN `brands` b ON p.brand_id = b.id
             WHERE p.is_active = 1 AND p.is_new = 1
             ORDER BY p.id DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy danh sách xe đang khuyến mãi
     */
    public function getOnSale(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name, b.name AS brand_name 
             FROM `products` p
             JOIN `categories` c ON p.category_id = c.id
             LEFT JOIN `brands` b ON p.brand_id = b.id
             WHERE p.is_active = 1 AND p.sale_price IS NOT NULL AND p.sale_price > 0 AND p.sale_price < p.price
             ORDER BY (p.price - p.sale_price) DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Chi tiết sản phẩm theo slug cùng danh mục và ảnh phụ
     */
    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug, b.name AS brand_name 
             FROM `products` p
             JOIN `categories` c ON p.category_id = c.id
             LEFT JOIN `brands` b ON p.brand_id = b.id
             WHERE p.slug = :slug AND p.is_active = 1
             LIMIT 1"
        );
        $stmt->execute([':slug' => $slug]);
        $product = $stmt->fetch();

        if (!$product) {
            return null;
        }

        // Lấy danh sách ảnh phụ
        $product['gallery'] = $this->getImages($product['id']);
        // Lấy danh sách biến thể
        $product['variants'] = $this->getVariants($product['id']);

        return $product;
    }

    public function getImages(int $productId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `product_images` WHERE `product_id` = :pid ORDER BY `sort_order` ASC, `id` ASC"
        );
        $stmt->execute([':pid' => $productId]);
        return $stmt->fetchAll();
    }

    public function getVariants(int $productId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `product_variants` WHERE `product_id` = :pid ORDER BY `id` ASC"
        );
        $stmt->execute([':pid' => $productId]);
        return $stmt->fetchAll();
    }

    /**
     * Lọc và tìm kiếm sản phẩm với nhiều tiêu chí phong phú
     */
    public function filterProducts(array $filters, int $page = 1, int $perPage = 9): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $where = ["p.is_active = 1"];
        $params = [];

        // Tìm theo từ khóa
        if (!empty($filters['q'])) {
            $where[] = "(p.name LIKE :q OR p.sku LIKE :q OR p.description LIKE :q)";
            $params[':q'] = '%' . trim($filters['q']) . '%';
        }

        // Lọc danh mục
        if (!empty($filters['category_id'])) {
            $where[] = "p.category_id = :category_id";
            $params[':category_id'] = (int)$filters['category_id'];
        }

        // Lọc thương hiệu
        if (!empty($filters['brand_id'])) {
            $where[] = "p.brand_id = :brand_id";
            $params[':brand_id'] = (int)$filters['brand_id'];
        }

        // Lọc theo khoảng giá
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $where[] = "COALESCE(p.sale_price, p.price) >= :min_price";
            $params[':min_price'] = (float)$filters['min_price'];
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $where[] = "COALESCE(p.sale_price, p.price) <= :max_price";
            $params[':max_price'] = (float)$filters['max_price'];
        }

        // Lọc theo tốc độ tối đa (km/h)
        if (!empty($filters['speed'])) {
            switch ($filters['speed']) {
                case 'under50':
                    $where[] = "p.max_speed <= 50";
                    break;
                case '50to80':
                    $where[] = "p.max_speed > 50 AND p.max_speed <= 80";
                    break;
                case 'over80':
                    $where[] = "p.max_speed > 80";
                    break;
            }
        }

        // Lọc theo quãng đường (km)
        if (!empty($filters['distance'])) {
            switch ($filters['distance']) {
                case 'under100':
                    $where[] = "p.max_distance < 100";
                    break;
                case '100to180':
                    $where[] = "p.max_distance >= 100 AND p.max_distance <= 180";
                    break;
                case 'over180':
                    $where[] = "p.max_distance > 180";
                    break;
            }
        }

        // Lọc theo pin
        if (!empty($filters['battery'])) {
            $where[] = "p.battery_type LIKE :battery";
            $params[':battery'] = '%' . $filters['battery'] . '%';
        }

        // Sắp xếp
        $sort = $filters['sort'] ?? 'newest';
        $orderBy = match ($sort) {
            'price_asc'  => 'COALESCE(p.sale_price, p.price) ASC',
            'price_desc' => 'COALESCE(p.sale_price, p.price) DESC',
            'name_asc'   => 'p.name ASC',
            'bestseller' => 'p.view_count DESC',
            default      => 'p.id DESC',
        };

        $whereClause = implode(' AND ', $where);

        // Đếm tổng
        $countSql = "SELECT COUNT(*) FROM `products` p WHERE {$whereClause}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $totalItems = (int)$countStmt->fetchColumn();
        $totalPages = (int)ceil($totalItems / $perPage);

        // Lấy dữ liệu
        $sql = "SELECT p.*, c.name AS category_name, b.name AS brand_name 
                FROM `products` p
                JOIN `categories` c ON p.category_id = c.id
                LEFT JOIN `brands` b ON p.brand_id = b.id
                WHERE {$whereClause}
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        return [
            'data'        => $items,
            'total'       => $totalItems,
            'per_page'    => $perPage,
            'page'        => $page,
            'total_pages' => $totalPages,
            'has_prev'    => $page > 1,
            'has_next'    => $page < $totalPages,
        ];
    }

    /**
     * Lấy các sản phẩm liên quan thông minh (cùng danh mục hoặc cùng thương hiệu)
     */
    public function getRelated(int $productId, int $categoryId, int $limit = 4): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name 
             FROM `products` p
             JOIN `categories` c ON p.category_id = c.id
             WHERE p.id != :pid AND p.category_id = :cid AND p.is_active = 1
             ORDER BY p.id DESC LIMIT :limit"
        );
        $stmt->bindValue(':pid', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':cid', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function incrementViewCount(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE `products` SET `view_count` = `view_count` + 1 WHERE `id` = :id");
        $stmt->execute([':id' => $id]);
    }

    public function decreaseStock(int $id, int $quantity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `products` SET `stock_quantity` = GREATEST(0, `stock_quantity` - :qty) WHERE `id` = :id"
        );
        return $stmt->execute([':qty' => $quantity, ':id' => $id]);
    }

    public function increaseStock(int $id, int $quantity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `products` SET `stock_quantity` = `stock_quantity` + :qty WHERE `id` = :id"
        );
        return $stmt->execute([':qty' => $quantity, ':id' => $id]);
    }
}
