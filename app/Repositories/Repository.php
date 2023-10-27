<?php

namespace App\Repositories;


use App\Support\Repositories\BaseRepository;

class Repository extends BaseRepository
{
    public function rawQuery($sql, $params = [], $row = false)
    {
        $res = $this->raw($sql, $params);

        return $this->rawAsArray($res, $row);
    }

    public function logRequest($data)
    {
        $res = $this->insert("cliniccentral.laravel_logs", $data);

        return $res;
    }

    public function deleteRequestLog($id)
    {
        $res = $this->delete("cliniccentral.laravel_logs", ["id" => $id]);

        return $res;
    }

    public function getRequests()
    {
        $res = $this->raw("SELECT Route, TIMESTAMPDIFF(SECOND, DataHora , CURRENT_TIMESTAMP()) as TempoExecucao, Body
            FROM cliniccentral.laravel_logs");

        return $this->rawAsArray($res);
    }
}