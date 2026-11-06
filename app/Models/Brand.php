<?php

namespace App\Models;

class Brand extends Model
{
    protected string $table = 'brands';

    public function allActive(): array
    {
        return $this->where('is_active', 1, '=', 'id ASC');
    }
}
