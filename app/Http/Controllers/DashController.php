<?php

namespace App\Http\Controllers;

use App\Services\ProductsService;
use App\Services\ListaSubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashController extends Controller
{
    private $productsService;
    public function __construct()
    {
        $this->productsService = new ProductsService();
    }
    public function index()
    {
        return view('dash.index');
    }
    public function getTotalCategoryStock()
    {
        $dados = $this->productsService->getTotalCategoryStock();

        $labels = $dados->pluck('label')->toArray();
        $data = $dados->pluck('value')->toArray();
        $cores = $dados->pluck('cor')->toArray();

        $dadosFormatados = [
            'labels' => $labels,
            'data' => $data,
            'cores' => $cores,
        ];

        return response()->json($dadosFormatados);
    }

}
