<?php

namespace App\Models;

class Banner extends Model
{
    protected string $table = 'banners';

    public function getActiveBanners(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM `banners` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` DESC"
        );
        return $stmt->fetchAll();
    }
}
