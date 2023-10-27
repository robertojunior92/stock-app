<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method paginate()
 */
class Product extends \App\Support\Repositories\BaseRepository
{
    public function find($id)
    {
        return parent::find($id);
    }

    public function getProducts(): array
    {

        $sql = "select id from products";

        return $this->raw($sql);
    }

}
