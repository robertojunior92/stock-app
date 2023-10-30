<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\ListaSubstituicaoRepository;
use App\Support\Repositories\BaseRepository;
use function PHPUnit\Framework\isNull;

class CategoryService extends Controller
{
    private $categoryRepository;

    function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function insertCategory($category_name)
    {
        return $this->categoryRepository->insertCategory($category_name);
    }
}
