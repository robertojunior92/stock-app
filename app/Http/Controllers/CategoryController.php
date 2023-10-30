<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Services\ProductsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Constants\Constants;

class CategoryController extends Controller
{
    private $categoryService;
    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }
    public function productCategoryRegistration()
    {
        return view('products.product-category-registration');
    }
    public function insertCategory(Request $request)
    {
        $category_name   = $request->input('CategoryName');

        $res = $this->categoryService->insertCategory($category_name);

        if ($res == true)
        {
            return ["success"=>1, "mensagem" =>"Categoria Incluido com Sucesso" ];
        }
        else
        {
            return ["success"=>0, "mensagem" =>"Erro ao Incluir o Registro" ];
        }
    }

}
