<?php

namespace App\Http\Controllers;

use App\Services\TipoMacroService;
use Illuminate\Http\Request;

class TipoMacroController extends Controller
{
    private $tipomacroService;
    public function __construct()
    {
        $this->tipomacroService = new TipoMacroService();
    }
    function index()
    {
        return view('tipo-macro');
    }
    public function getTipoMacroAdmin(Request $request)
    {
        $offset = $request->input('offset');
        $limit = $request->input('limit');

        $result = $this->tipomacroService->getTipoMacroAdmin($offset, $limit);

        return response()->json($result);
    }
    public function searchTipoMacro(Request $request)
    {
        $input = $request->input('input');

        $result = $this->tipomacroService->getTipoMacroAdmin(0, 10, $input);

        return response()->json($result);
    }
    public function getDadosTipoMacroById(Request $request)
    {
        $id  = $request->input('id');
        $res = $this->tipomacroService->getTipoMacro(["id"=> $id]);
        $res = $res[0];
        return response()->json($res);
    }
    public function incluirTipoMacro(Request $request)
    {
        $dados = [
            'NomeTipoMacro' => $request->input('NomeTipoMacro'),
        ];
        $res = $this->tipomacroService->insertTipoMacro($dados);
        if (is_numeric($res))
        {
            return ["success"=>1, "mensagem" =>"Tipo Incluido com Sucesso" ];
        }
        else
        {
            return ["success"=>0, "mensagem" =>"Erro ao Incluir o Registro" ];
        }
    }
    public function updateTipoMacro(Request $request)
    {
        $id  = $request->input('id');
        $dados = [
            'NomeTipoMacro' => $request->input('NomeTipoMacro'),
        ];
        $res = $this->tipomacroService->updateTipoMacro($dados, $id);
        if (is_numeric($res))
        {
            return ["success"=>1, "mensagem" =>"Alterado com Sucesso" ];
        }
        else
        {
            return ["success"=>0, "mensagem" =>"Erro ao alterar o Registro" ];
        }
    }
    public function deleteTipoMacro(Request $request)
    {
        $tipo_macroID = $request->input("tipo_macroID");

        $result = $this->tipomacroService->deleteTipoMacro($tipo_macroID);

        return response()->json($result);
    }
}
