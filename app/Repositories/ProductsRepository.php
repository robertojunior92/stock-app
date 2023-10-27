<?php

namespace App\Repositories;

use App\Constants\Constants;
use App\Models\Product;
use App\Support\Repositories\BaseRepository;



class ProductsRepository extends BaseRepository
{
    private function montaWhereString($whereArray = false)
    {
        $sqlWhere = "";
        if (!$whereArray) { return $sqlWhere; }

        if (isset($whereArray["wherestring"])) { return " AND ". $whereArray["wherestring"]; }
        if (count($whereArray)>0)
        {
            foreach ($whereArray as $key => $value) {
                if ( strlen($value)>0)
                {
                    $sqlWhere .= " AND ".$key." ='".$value."'";
                }
            }
        }
        return $sqlWhere;
    }
    public function getCategory($whereArray = false)
    {
        $sqlWhere  = $this->montaWhereString($whereArray);
        $sql = "SELECT id, category_name
                FROM product_category
	            WHERE true {$sqlWhere} ORDER BY category_name";
        $result = $this->raw($sql);
        return $this->rawAsArray($result);
    }
    public function insertProducts($product_name, $category, $unit_price, $quantity_in_stock)
    {
        $now = date('Y-m-d H:i:s');

        $id = $this->insert('products', [
            'product_name'      => $product_name,
            'category'          => $category,
            'unit_price'        => $unit_price,
            'date_added'        => $now,
            'status'            => Constants::IN_STOCK,
            'quantity_in_stock' => $quantity_in_stock
        ]);

        if ($id) {
            $this->insertStockMovements($id, Constants::ENTRY_STOCK, $quantity_in_stock, $now, Constants::ENTRY_PRODUCT_DESC);
        }
        return true;
    }
    public function getProducts($offset = false, $limit = false, $input = false)
    {
        $where="";
        if($input){
            $where = " where p.product_name LIKE '%{$input}%' ";
        }
        if($offset === false){
            $offset = 0;
        }
        if($limit === false){
            $limit = 100;
        }
        $res = $this->raw("SELECT p.*, pc.category_name, ps.status_product_name
                               FROM products p
                               INNER JOIN product_category pc   ON pc.id        = p.category
                               INNER JOIN product_status ps     ON ps.status    = p.status
                               {$where}
                               ORDER BY p.product_name LIMIT ? OFFSET ?",
            [floatval($limit), floatval($offset)]);


        return $this->rawAsArray($res);
    }
    public function getProductsInStock($offset, $limit, $input = false)
    {
        $where="";
        if($input){
            $where = " where p.status = '".Constants::IN_STOCK."' and p.product_name LIKE '%{$input}%' ";
        };
        $res = $this->raw("SELECT p.*, pc.category_name, ps.status_product_name
                               FROM products p
                               INNER JOIN product_category pc   ON pc.id        = p.category
                               INNER JOIN product_status ps     ON ps.status    = p.status
                               {$where}
                               ORDER BY p.product_name LIMIT ? OFFSET ?",
            [floatval($limit), floatval($offset)]);

        return $this->rawAsArray($res);
    }
    public function updateProduct(array $data, $id)
    {
        return $this->update("products", $data, ["id" => $id]);
    }
    private function insertStockMovements($product_id, $movement_type, $quantity, $now, $description)
    {
        return $this->insert('stock_movements', [
            'product_id'    => $product_id,
            'movement_type' => $movement_type,
            'quantity'      => $quantity,
            'date'          => $now,
            'description'   => $description


        ]);

    }
    public function findProductById($id)
    {

        $sql = "SELECT * FROM products WHERE id = $id";
        $result = $this->raw($sql);
        return $this->rawAsArray($result, true);
    }
    public function getTotalStock()
    {
        $sql = "SELECT SUM(p.quantity_in_stock) totalStock  FROM products p WHERE status = '".Constants::IN_STOCK."' ";
        $result = $this->raw($sql);
        return $this->rawAsArray($result, 'totalStock');
    }
    public function getTotalEntryStock()
    {
        $sql = "SELECT SUM(p.quantity) totalEntryStock FROM stock_movements p WHERE movement_type = '".Constants::ENTRY_STOCK."' ";
        $result = $this->raw($sql);
        return $this->rawAsArray($result, 'totalEntryStock');
    }
    public function getTotalOutStock()
    {
        $sql = "SELECT SUM(p.quantity) totalOutStock FROM stock_movements p WHERE movement_type = '".Constants::OUT_STOCK."' ";
        $result = $this->raw($sql);
        return $this->rawAsArray($result, 'totalOutStock');
    }
    public function getTotalValueOutStock()
    {
        $sql = "SELECT s.quantity * p.unit_price totalValueOutStock
                FROM stock_movements s
                INNER JOIN products p ON p.id = s.product_id
                WHERE movement_type = '".Constants::OUT_STOCK."' GROUP BY s.quantity, p.unit_price";
        $result = $this->raw($sql);
        return $this->rawAsArray($result, 'totalValueOutStock');
    }
    public function getTotalCategoryStock()
    {
        $sql = "SELECT s.quantity * p.unit_price totalValueOutStock
                FROM stock_movements s
                INNER JOIN products p ON p.id = s.product_id
                WHERE movement_type = '".Constants::OUT_STOCK."' GROUP BY s.quantity, p.unit_price";
        $result = $this->raw($sql);
        return $this->rawAsArray($result,);
    }
}
