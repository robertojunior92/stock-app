<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Repositories\TipoPreparoRepository;

class TipoPreparoService extends Controller
{
    private $tipopreparoRepository;

    function __construct()
    {
        $this->tipopreparoRepository = new TipoPreparoRepository();
    }
    public function getTipoPreparoAdmin($offset, $limit, $input = false)
    {
        return $this->tipopreparoRepository->getTipoPreparoAdmin($offset, $limit, $input);
    }
    public function getTipoPreparo($whereArray = false)
    {
        return $this->tipopreparoRepository->getTipoPreparo($whereArray);
    }
    public function insertTipoPreparo($data)
    {
        return $this->tipopreparoRepository->insertTipoPreparo($data);
    }
    public function updateTipoPreparo($data,$id)
    {
        return $this->tipopreparoRepository->updateTipoPreparo($data,$id);
    }
    public function deleteTipoPreparo($tipo_macroID)
    {
        return $this->tipopreparoRepository->deleteTipoPreparo($tipo_macroID);
    }



}
