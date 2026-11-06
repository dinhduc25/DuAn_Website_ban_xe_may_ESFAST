<?php

namespace App\Models;

use PDO;

class Payment extends Model
{
    protected string $table = 'payments';

    public function allWithOrders(int $page = 1, int $perPage = 10, string $status = ''): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $where = ["1=1"];
        $params = [];

        if (!empty($status)) {
            $where[] = "pay.status = :status";
            $params[':status'] = $status;
        }

        $whereClause = implode(' AND ', $where);

        $countSql = "SELECT COUNT(*) FROM `payments` pay WHERE {$whereClause}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();
        $totalPages = (int)ceil($total / $perPage);

        $sql = "SELECT pay.*, o.order_code, o.customer_name, o.customer_phone
                FROM `payments` pay
                JOIN `orders` o ON pay.order_id = o.id
                WHERE {$whereClause}
                ORDER BY pay.id DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        return [
            'data'        => $items,
            'total'       => $total,
            'per_page'    => $perPage,
            'page'        => $page,
            'total_pages' => $totalPages,
            'has_prev'    => $page > 1,
            'has_next'    => $page < $totalPages,
        ];
    }
}
