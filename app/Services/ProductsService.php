<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Repositories\ProductsRepository;
use App\Repositories\ListaSubstituicaoRepository;
use App\Support\Repositories\BaseRepository;
use function PHPUnit\Framework\isNull;

class ProductsService extends Controller
{
    private $productsRepository;

    function __construct()
    {
        $this->productsRepository = new ProductsRepository();
    }
    public function getCategory($whereArray = false)
    {
        return $this->productsRepository->getCategory($whereArray);
    }

    public function insertProducts($product_name, $category, $unit_price, $quantity_in_stock)
    {
        return $this->productsRepository->insertProducts($product_name, $category, $unit_price, $quantity_in_stock);
    }
    public function getProducts($offset = false, $limit = false, $input = false)
    {
        return $this->productsRepository->getProducts($offset, $limit, $input);
    }

    public function getProductsInStock($offset, $limit, $input = false)
    {
        return $this->productsRepository->getProductsInStock($offset, $limit, $input);
    }
    public function updateProduct(array $data, $id)
    {
        return $this->productsRepository->updateProduct($data, $id);
    }
    public function findProductById($id)
    {
        return $this->productsRepository->findProductById($id);
    }
    public function getTotalStock()
    {
        return $this->productsRepository->getTotalStock();
    }
    public function getTotalEntryStock()
    {
        return $this->productsRepository->getTotalEntryStock();
    }
    public function getTotalOutStock()
    {
        return $this->productsRepository->getTotalOutStock();
    }
    public function getTotalValueOutStock()
    {
        return $this->productsRepository->getTotalValueOutStock();
    }
    public function getTotalCategoryStock()
    {
        return $this->productsRepository->getTotalCategoryStock();
    }
}
