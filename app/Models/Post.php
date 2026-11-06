<?php

namespace App\Models;

use PDO;

class Post extends Model
{
    protected string $table = 'posts';

    public function getLatest(int $limit = 3): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `posts` WHERE `is_active` = 1 ORDER BY `id` DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
