<?php

namespace App\Repositories;

use App\Support\Repositories\BaseRepository;

class TipoPreparoRepository extends BaseRepository
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
    public function getTipoPreparoAdmin($offset, $limit, $input = false)
    {
        $where="";
        if($input){
            $where = " where ta.Preparo like '%{$input}%' ";
        };
        $res = $this->raw("SELECT ta.*
                                FROM tipo_preparo ta
                                {$where}
                                ORDER BY ta.Preparo LIMIT ? OFFSET ?",
            [floatval($limit), floatval($offset)]);

        return $this->rawAsArray($res);
    }
    public function getTipoPreparo($whereArray = false)
    {
        $sqlWhere  = $this->montaWhereString($whereArray);
        $sql = "SELECT id, Preparo
                FROM tipo_preparo
	            WHERE true {$sqlWhere} ORDER BY Preparo";
        $result = $this->raw($sql);
        return $this->rawAsArray($result);
    }
    public function insertTipoPreparo($data)
    {
        return $this->insert("tipo_preparo", $data);
    }
    public function updateTipoPreparo($data, $id)
    {
        return $this->update("tipo_preparo", $data, ["id" => $id]);
    }
    public function deleteTipoPreparo($tipo_preparoID)
    {
        return $this->delete("tipo_preparo", $tipo_preparoID);
    }


}
