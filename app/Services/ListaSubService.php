<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Repositories\ListaSubstituicaoRepository;
class ListaSubService extends Controller
{
    private $listRepository;

    function __construct()
    {
        $this->listRepository = new ListaSubstituicaoRepository();
    }
    public function getListSub($data)
    {
        return $this->listRepository->getListSub($data);
    }

}
