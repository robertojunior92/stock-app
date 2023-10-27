<?php

namespace App\Http\Controllers;

use App\Services\ProductsService;
use App\Services\ListaSubService;
use App\Services\TipoAlimentarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoAlimentarController extends Controller
{
    private $tipoalimentarService;
    public function __construct()
    {
        $this->tipoalimentarService = new TipoAlimentarService();
    }
    function index()
    {
        return view('tipo-alimentar');
    }
    public function getTipoAlimentarAdmin(Request $request)
    {
        $offset = $request->input('offset');
        $limit = $request->input('limit');

        $result = $this->tipoalimentarService->getTipoAlimentarAdmin($offset, $limit);

        return response()->json($result);
    }
    public function searchTipoAlimentar(Request $request)
    {
        $input = $request->input('input');

        $result = $this->tipoalimentarService->getTipoAlimentarAdmin(0, 10, $input);

        return response()->json($result);
    }
    public function getDadosTipoAlimentarById(Request $request)
    {
        $id  = $request->input('id');
        $res = $this->tipoalimentarService->getTipoAlimentar(["id"=> $id]);
        $res = $res[0];
        return response()->json($res);
    }
    public function incluirTipoAlimentar(Request $request)
    {
        $dados = [
            'NomeTipoAlimentar' => $request->input('NomeTipoAlimentar'),
        ];
        $res = $this->tipoalimentarService->insertTipoAlimentar($dados);
        if (is_numeric($res))
        {
            return ["success"=>1, "mensagem" =>"Tipo Incluido com Sucesso" ];
        }
        else
        {
            return ["success"=>0, "mensagem" =>"Erro ao Incluir o Registro" ];
        }
    }
    public function updateTipoAlimentar(Request $request)
    {
        $id  = $request->input('id');
        $dados = [
            'NomeTipoAlimentar' => $request->input('NomeTipoAlimentar'),
        ];
        $res = $this->tipoalimentarService->updateTipoAlimentar($dados, $id);
        if (is_numeric($res))
        {
            return ["success"=>1, "mensagem" =>"Alterado com Sucesso" ];
        }
        else
        {
            return ["success"=>0, "mensagem" =>"Erro ao alterar o Registro" ];
        }
    }
    public function deleteTipoAlimentar(Request $request)
    {
        $tipo_alimentarID = $request->input("tipo_alimentarID");

        $result = $this->tipoalimentarService->deleteTipoAlimentar($tipo_alimentarID);

        return response()->json($result);
    }
}
