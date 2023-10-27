<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Repositories\TipoMacroRepository;

class TipoMacroService extends Controller
{
    private $tipomacroRepository;

    function __construct()
    {
        $this->tipomacroRepository = new TipoMacroRepository();
    }
    public function getTipoMacroAdmin($offset, $limit, $input = false)
    {
        return $this->tipomacroRepository->getTipoMacroAdmin($offset, $limit, $input);
    }
    public function getTipoMacro($whereArray = false)
    {
        return $this->tipomacroRepository->getTipoMacro($whereArray);
    }
    public function insertTipoMacro($data)
    {
        return $this->tipomacroRepository->insertTipoMacro($data);
    }
    public function updateTipoMacro($data,$id)
    {
        return $this->tipomacroRepository->updateTipoMacro($data,$id);
    }
    public function deleteTipoMacro($tipo_macroID)
    {
        return $this->tipomacroRepository->deleteTipoMacro($tipo_macroID);
    }



}
