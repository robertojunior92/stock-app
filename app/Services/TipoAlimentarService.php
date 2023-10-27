<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Repositories\ProductsRepository;
use App\Repositories\ListaSubstituicaoRepository;
use App\Repositories\TipoAlimentarRepository;
use App\Repositories\TipoMacroRepository;
use function PHPUnit\Framework\isNull;

class TipoAlimentarService extends Controller
{
    private $tipoalimentarRepository;

    function __construct()
    {
        $this->tipoalimentarRepository = new TipoAlimentarRepository();
    }
    public function getTipoAlimentarAdmin($offset, $limit, $input = false)
    {
        return $this->tipoalimentarRepository->getTipoAlimentarAdmin($offset, $limit, $input);
    }
    public function getTipoAlimentar($whereArray = false)
    {
        return $this->tipoalimentarRepository->getTipoAlimentar($whereArray);
    }
    public function insertTipoAlimentar($data)
    {
        return $this->tipoalimentarRepository->insertTipoAlimentar($data);
    }
    public function updateTipoAlimentar($data,$id)
    {
        return $this->tipoalimentarRepository->updateTipoAlimentar($data,$id);
    }
    public function deleteTipoAlimentar($tipo_alimentarID)
    {
        return $this->tipoalimentarRepository->deleteTipoAlimentar($tipo_alimentarID);
    }



}
