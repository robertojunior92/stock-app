<?php

namespace App\Http\Controllers;

use App\Services\ProductsService;
use App\Services\ListaSubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

}
