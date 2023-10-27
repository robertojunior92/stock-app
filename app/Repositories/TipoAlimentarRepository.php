<?php

namespace App\Repositories;

use App\Support\Repositories\BaseRepository;

class TipoAlimentarRepository extends BaseRepository
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
    public function getTipoAlimentarAdmin($offset, $limit, $input = false)
    {
        $where="";
        if($input){
            $where = " where ta.NomeTipoAlimentar like '%{$input}%' ";
        };
        $res = $this->raw("SELECT ta.*
                                FROM tipo_alimentar ta
                                {$where}
                                ORDER BY ta.NomeTipoAlimentar LIMIT ? OFFSET ?",
            [floatval($limit), floatval($offset)]);

        return $this->rawAsArray($res);
    }
    public function getTipoAlimentar($whereArray = false)
    {
        $sqlWhere  = $this->montaWhereString($whereArray);
        $sql = "SELECT id, NomeTipoAlimentar
                FROM tipo_alimentar
	            WHERE true {$sqlWhere} ORDER BY NomeTipoAlimentar";
        $result = $this->raw($sql);
        return $this->rawAsArray($result);
    }
    public function insertTipoAlimentar($data)
    {
        return $this->insert("tipo_alimentar", $data);
    }
    public function updateTipoAlimentar($data, $id)
    {
        return $this->update("tipo_alimentar", $data, ["id" => $id]);
    }
    public function deleteTipoAlimentar($tipo_alimentarID)
    {
        return $this->delete("tipo_alimentar", $tipo_alimentarID);
    }


}
