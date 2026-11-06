<?php

namespace App\Models;

use PDO;

class User extends Model
{
    protected string $table = 'users';

    public function findByUsername(string $username): ?array
    {
        return $this->findBy('username', $username);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    public function findByUsernameOrEmail(string $credential): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `users` WHERE `username` = :cred OR `email` = :cred LIMIT 1"
        );
        $stmt->execute([':cred' => $credential]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function register(array $data): int
    {
        // Luôn đảm bảo mã hóa mật khẩu và role là customer
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['role'] = 'customer';
        $data['status'] = 'active';

        return $this->create($data);
    }

    public function updatePassword(int $id, string $plainPassword): bool
    {
        $hash = password_hash($plainPassword, PASSWORD_BCRYPT);
        return $this->update($id, ['password' => $hash]);
    }

    public function getCustomers(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $where = ["u.role = 'customer'"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(u.username LIKE :s OR u.email LIKE :s OR u.full_name LIKE :s OR u.phone LIKE :s)";
            $params[':s'] = '%' . trim($search) . '%';
        }

        $whereClause = implode(' AND ', $where);

        $countSql = "SELECT COUNT(*) FROM `users` u WHERE {$whereClause}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $totalItems = (int)$countStmt->fetchColumn();
        $totalPages = (int)ceil($totalItems / $perPage);

        $sql = "SELECT u.*, COUNT(o.id) AS order_count, COALESCE(SUM(o.total_amount), 0) AS total_spent
                FROM `users` u
                LEFT JOIN `orders` o ON u.id = o.user_id AND o.order_status = 'completed'
                WHERE {$whereClause}
                GROUP BY u.id
                ORDER BY u.id DESC
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

    public function getAdmins(): array
    {
        return $this->where('role', 'admin', '=', 'id ASC');
    }
}
