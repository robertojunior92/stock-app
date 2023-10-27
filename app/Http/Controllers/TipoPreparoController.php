<?php

namespace App\Http\Controllers;

use App\Services\TipoPreparoService;
use Illuminate\Http\Request;
class TipoPreparoController extends Controller
{
    private $tipopreparoService;
    public function __construct()
    {
        $this->tipopreparoService = new TipoPreparoService();
    }
    function index()
    {
        return view('tipo-preparo');
    }
    public function getTipoPreparoAdmin(Request $request)
    {
        $offset = $request->input('offset');
        $limit = $request->input('limit');

        $result = $this->tipopreparoService->getTipoPreparoAdmin($offset, $limit);

        return response()->json($result);
    }
    public function searchTipoPreparo(Request $request)
    {
        $input = $request->input('input');

        $result = $this->tipopreparoService->getTipoPreparoAdmin(0, 10, $input);

        return response()->json($result);
    }
    public function getDadosTipoPreparoById(Request $request)
    {
        $id  = $request->input('id');
        $res = $this->tipopreparoService->getTipoPreparo(["id"=> $id]);
        $res = $res[0];
        return response()->json($res);
    }
    public function incluirTipoPreparo(Request $request)
    {
        $dados = [
            'NomeTipoPreparo' => $request->input('NomeTipoPreparo'),
        ];
        $res = $this->tipopreparoService->insertTipoPreparo($dados);
        if (is_numeric($res))
        {
            return ["success"=>1, "mensagem" =>"Tipo Incluido com Sucesso" ];
        }
        else
        {
            return ["success"=>0, "mensagem" =>"Erro ao Incluir o Registro" ];
        }
    }
    public function updateTipoPreparo(Request $request)
    {
        $id  = $request->input('id');
        $dados = [
            'NomeTipoPreparo' => $request->input('NomeTipoPreparo'),
        ];
        $res = $this->tipopreparoService->updateTipoPreparo($dados, $id);
        if (is_numeric($res))
        {
            return ["success"=>1, "mensagem" =>"Alterado com Sucesso" ];
        }
        else
        {
            return ["success"=>0, "mensagem" =>"Erro ao alterar o Registro" ];
        }
    }
    public function deleteTipoPreparo(Request $request)
    {
        $tipo_preparoID = $request->input("tipo_preparoID");

        $result = $this->tipopreparoService->deleteTipoPreparo($tipo_preparoID);

        return response()->json($result);
    }
}
