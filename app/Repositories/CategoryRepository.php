<?php

namespace App\Repositories;

use App\Constants\Constants;
use App\Support\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository
{
    public function insertCategory($category_name)
    {
        $now = date('Y-m-d H:i:s');

        return $this->insert('product_category', [
            'category_name' => $category_name,
            'date_update'   => $now
        ]);
    }
}
