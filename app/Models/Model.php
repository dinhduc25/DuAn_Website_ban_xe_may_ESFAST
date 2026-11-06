<?php

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBy(string $column, mixed $value): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$column}` = :val LIMIT 1");
        $stmt->execute([':val' => $value]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function all(string $orderBy = 'id DESC'): array
    {
        $stmt = $this->db->query("SELECT * FROM `{$this->table}` ORDER BY {$orderBy}");
        return $stmt->fetchAll();
    }

    public function where(string $column, mixed $value, string $operator = '=', string $orderBy = 'id DESC'): array
    {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$column}` {$operator} :val ORDER BY {$orderBy}");
        $stmt->execute([':val' => $value]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $columns = implode('`, `', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO `{$this->table}` (`{$columns}`) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        $params = [];
        foreach ($data as $key => $val) {
            $params[':' . $key] = $val;
        }

        $stmt->execute($params);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $setClauses = [];
        $params = [':id' => $id];

        foreach ($data as $key => $val) {
            $setClauses[] = "`{$key}` = :{$key}";
            $params[':' . $key] = $val;
        }

        $setString = implode(', ', $setClauses);
        $sql = "UPDATE `{$this->table}` SET {$setString} WHERE `{$this->primaryKey}` = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page = 1, int $perPage = 12, string $where = '', array $params = [], string $orderBy = 'id DESC'): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $countSql = "SELECT COUNT(*) FROM `{$this->table}`";
        if (!empty($where)) {
            $countSql .= " WHERE {$where}";
        }
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $totalItems = (int)$countStmt->fetchColumn();
        $totalPages = (int)ceil($totalItems / $perPage);

        $sql = "SELECT * FROM `{$this->table}`";
        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }
        $sql .= " ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
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
}
