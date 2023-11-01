<?php

namespace App\Http\Controllers;

use App\Services\ProductsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Constants\Constants;

class ProductsController extends Controller
{
    private $productsService;
    public function __construct()
    {
        $this->productsService = new ProductsService();
    }
    function index()
    {
        return view('products.index');
    }
    public function getCategory(): \Illuminate\Http\JsonResponse
    {
        $res  = $this->productsService->getCategory();
        return response()->json($res);
    }
    public function insertProducts(Request $request)
    {

        $product_name       = $request->input('ProductName');
        $category           = $request->input('CategoryID');
        $unit_price         = $request->input('UnitPrice');
        $quantity_in_stock  = $request->input('qtd');

        if(is_null($quantity_in_stock)){
            $quantity_in_stock = 1;
        }

        $res = $this->productsService->insertProducts($product_name, $category, $unit_price, $quantity_in_stock);

        if ($res == true)
        {
            return ["success"=>1, "mensagem" =>"Produto Incluido com Sucesso" ];
        }
        else
        {
            return ["success"=>0, "mensagem" =>"Erro ao Incluir o Registro" ];
        }
    }
    public function updateProduct(Request $request)
    {
        $type_entry = $request->input('type_entry');

        if ($type_entry === Constants::MOVEMENT_ENTRY_STOCK){

            $product_id = $request->input('ProductID');
            $product = $this->productsService->findProductById($product_id);

            if (!$product) {
                return ["success" => 0, "mensagem" => "Produto não encontrado"];
            }

            $newQuantity = $product['quantity_in_stock'] + $request->input('qtd');

            $data = [
                'product_name'      => $product['product_name'],
                'category'          => $product['category'],
                'unit_price'        => $product['unit_price'],
                'status'            => $product['status'],
                'image'             => $product['image'],
                'date_out'          => null,
                'quantity_in_stock' => $newQuantity, // Atualiza a quantidade,
                'type_movement'     => $type_entry,
                'desc_movement'     => Constants::MOVEMENT_ENTRY_PRODUCT_DESC
            ];

            // Chame o serviço para atualizar o produto
            $this->productsService->updateProduct($data, $product_id);
        }else if ($type_entry === Constants::MOVEMENT_EXIT_STOCK){
            $product_id = $request->input('ProductID');
            $product = $this->productsService->findProductById($product_id);

            if (!$product) {
                return ["success" => 0, "mensagem" => "Produto não encontrado"];
            }

            // Quantidade a ser subtraída
            $quantity_to_subtract = $request->input('qtd');

            // Verifica se a quantidade a ser subtraída não é maior que a quantidade em estoque
            if ($quantity_to_subtract > $product['quantity_in_stock']) {
                return ["success" => 0, "mensagem" => "Quantidade insuficiente no estoque"];
            }

            $newQuantity = $product['quantity_in_stock'] - $request->input('qtd');

            $data = [
                'product_name'      => $product['product_name'],
                'category'          => $product['category'],
                'unit_price'        => $product['unit_price'],
                'status'            => $product['status'],
                'image'             => $product['image'],
                'date_out'          => null,
                'quantity_in_stock' => $newQuantity, // Atualiza a quantidade,
                'type_movement'     => $type_entry,
                'desc_movement'     => Constants::MOVEMENT_EXIT_PRODUCT_DESC
            ];

            // Chame o serviço para atualizar o produto
            $this->productsService->updateProduct($data, $product_id);
        }

        return ["success" => 1, "mensagem" => "Produto Atualizado com Sucesso"];
    }
    public function searchProducts(Request $request)
    {
        $input = $request->input('input');

        $result = $this->productsService->getProducts(0, 50, $input);

        return response()->json($result);
    }
    public function searchProductsInStock(Request $request)
    {
        $input = $request->input('input');

        $result = $this->productsService->getProductsInStock(0, 50, $input);

        return response()->json($result);
    }
    public function productQuickRegistration()
    {
        return view('products.product-quick-registration');
    }
    public function productFullRegistration()
    {
        return view('products.product-full-registration');
    }
    public function productOutput()
    {
        return view('products.product-output');
    }
    public function productEntry()
    {
        return view('products.product-entry');
    }
    public function getProducts(): \Illuminate\Http\JsonResponse
    {
        $res = $this->productsService->getProducts();

        return response()->json($res);
    }
    public function getTotalStock()
    {
        $totalStock = $this->productsService->getTotalStock();
        return response()->json(['totalStock' => $totalStock]);
    }
    public function getTotalEntryStock()
    {
        $totalEntryStock = $this->productsService->getTotalEntryStock();
        return response()->json(['totalEntryStock' => $totalEntryStock]);
    }
    public function getTotalOutStock()
    {
        $totalOutStock = $this->productsService->getTotalOutStock();
        return response()->json(['totalOutStock' => $totalOutStock]);
    }
    public function getTotalValueOutStock()
    {
        $totalValueOutStock = $this->productsService->getTotalValueOutStock();
        return response()->json(['totalValueOutStock' => $totalValueOutStock]);
    }
}
