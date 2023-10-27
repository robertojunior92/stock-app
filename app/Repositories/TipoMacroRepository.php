<?php

namespace App\Repositories;

use App\Support\Repositories\BaseRepository;

class TipoMacroRepository extends BaseRepository
{
    private function montaWhereString($whereArray = false)
    {
        $sqlWhere = "";
        if (!$whereArray) { return $sqlWhere; }

        if (isset($whereArray["wherestring"])) { return " AND ". $whereArray["wherestring"]; }
        if (count($whereArray)>0)
        {
            foreach ($whereArray as $key => $value) {
                if ( strlen($value)>0)
                {
                    $sqlWhere .= " AND ".$key." ='".$value."'";
                }
            }
        }
        return $sqlWhere;
    }
    public function getTipoMacroAdmin($offset, $limit, $input = false)
    {
        $where="";
        if($input){
            $where = " where ta.NomeMacro like '%{$input}%' ";
        };
        $res = $this->raw("SELECT ta.*
                                FROM tipo_macro ta
                                {$where}
                                ORDER BY ta.NomeMacro LIMIT ? OFFSET ?",
            [floatval($limit), floatval($offset)]);

        return $this->rawAsArray($res);
    }
    public function getTipoMacro($whereArray = false)
    {
        $sqlWhere  = $this->montaWhereString($whereArray);
        $sql = "SELECT id, NomeMacro
                FROM tipo_macro
	            WHERE true {$sqlWhere} ORDER BY NomeMacro";
        $result = $this->raw($sql);
        return $this->rawAsArray($result);
    }
    public function insertTipoMacro($data)
    {
        return $this->insert("tipo_macro", $data);
    }
    public function updateTipoMacro($data, $id)
    {
        return $this->update("tipo_macro", $data, ["id" => $id]);
    }
    public function deleteTipoMacro($tipo_macroID)
    {
        return $this->delete("tipo_macro", $tipo_macroID);
    }


}
