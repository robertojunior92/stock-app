<?php
/**
 * Created by PhpStorm.
 * User: MaiaVinicius
 * Date: 28/04/18
 * Time: 12:13
 */

namespace App\Support\Repositories;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use PDO;

abstract class BaseRepository
{
    /**
     * @var array
     */
    private $cachedData;

    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass;

       /**
     * @var EloquentQueryBuilder|QueryBuilder
     */
    protected $_query;
    /**
     * @return EloquentQueryBuilder|QueryBuilder
     */
    protected function newQuery()
    {
        return app($this->modelClass)->newQuery();
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function validate($data)
    {
        return app($this->modelClass)->validator($data);
    }

    /**
     * @param null $query
     * @param int $take
     * @param bool $paginate
     *
     * @return EloquentQueryBuilder[]|EloquentCollection|\Illuminate\Support\Collection
     */
    protected function doQuery($query = null, $take = false, $paginate = false)
    {
        if (is_null($query)) {
            $query = $this->newQuery();
        }
        if (true == $paginate) {
//			return $query->paginate( $take );
        }

        if ($take > 0 || false !== $take) {
            $query->take($take);
        }

        return $query->get();
    }

    /**
     * Retrieves a record by his id
     * If fail is true $ fires ModelNotFoundException.
     *
     * @param int $id
     * @param bool $fail
     *
     * @return Model
     */
    public function findByID($id, $cols = false)
    {
        $query = $this->newQuery();

        if ($cols) {
            $query->select($cols);
        }

        return $query->find($id);
    }

    public static function staticRawAsArray($rawResult, $row = false, $column = false, $std = false)
    {
        if ($std) {
            return $rawResult;
        }
        $res = json_decode(json_encode($rawResult), true);

        if ($row && $rawResult) {
            $res = $res[0];

            if ($column !== false && is_string($column)) {
                $res = $res[$column];
            }
        }

        return $res;
    }

    /**
     * @param array $rawResult
     * @param bool $row
     * @param bool $column
     *
     * @return array|bool
     */
    protected function rawAsArray($rawResult, $row = false, $column = false, $std = false)
    {
        return self::staticRawAsArray($rawResult, $row, $column, $std);
    }

    /**
     * @param $cacheData array
     * @param $cacheKeysNames
     * @param bool|string $cacheName
     *
     * @param bool $defaultColumn
     * @return array
     */
    public function setCacheResult($cacheData, $cacheKeysNames, $cacheName = false, $defaultColumn = false)
    {
        $_cached = [];
        if (is_string($cacheKeysNames)) {
            $cacheKeysNames = [$cacheKeysNames];
        }

        if (!$cacheName) {
            $cacheName = implode("/", $cacheKeysNames);
        }

        for ($i = 0; $i < count($cacheData); $i++) {
            $line = $cacheData[$i];
            $lineRow = $line;

            $key0 = $line[$cacheKeysNames[0]];

            if ($defaultColumn !== false) {
                $lineRow = $line[$defaultColumn];
            }

            $_cached[$key0] = [];

            if (isset($cacheKeysNames[1])) {
                $key1 = $line[$cacheKeysNames[1]];

                $_cached[$key0][$key1] = $lineRow;
            } else {
                $_cached[$key0] = $lineRow;
            }
        }
        $this->cachedData[$cacheName] = $_cached;

        return $this->cachedData;
    }

    /**
     * @param string|array $cacheKeys
     * @param bool|string $cacheName
     *
     * @return array|bool
     */
    public function getCachedData($cacheKeysValues, $cacheName = false)
    {
        if (!is_array($cacheKeysValues)) {
            $cacheKeysValues = [$cacheKeysValues];
        }

        if (!$cacheName) {
            $cacheName = implode("/", $cacheKeysValues);
        }

        try {
            if (isset($cacheKeysValues[1])) {
                $cachedData = @$this->cachedData[$cacheName][$cacheKeysValues[0]][$cacheKeysValues[1]];
            } else {
                $cachedData = @$this->cachedData[$cacheName][$cacheKeysValues[0]];
            }
        } catch (\Exception $e) {
            $cachedData = false;
        }

        return $cachedData;
    }

    public function getIndexesToCache($data, $indexName, $returnArray = false)
    {
        $indexes = [];
        $_indexes = [];
        $isInt = true;
        $isDate = false;

        if (is_array($data)) {
            for ($i = 0; $i < count($data); $i++) {
                if (isset($data[$i])) {
                    if (is_object($data[$i])) {
                        if (@$data[$i]->$indexName) {
                            $val = $data[$i]->$indexName;
                        } else {
                            $val = '';
                        }
                    } else {
                        if (isset($data[$i][$indexName])) {
                            $val = $data[$i][$indexName];
                        } else {
                            $val = "";
                        }
                    }

                    if (is_numeric($val) || $val === "") {
                        $val = intval($val);
                    } else {
                        $isInt = false;

                        if (strtotime(str_replace("/", "-", $val))) {
                            $isDate = true;
                            $val = "{$val}";
                        } else {
                            $val = "'{$val}'";
                        }

                    }

                    if (!isset($_indexes[$val])) {
                        $indexes[] = $val;
                        $_indexes[$val] = true;
                    }
                }

            }
        }

        if ($returnArray) {
            return [
                "isInt" => $isInt,
                "isDate" => $isDate,
                "data" => implode(",", $indexes)
            ];
        } else {
            return implode(",", $indexes);
        }
    }

    protected function idOrIds($entry)
    {
        if (!is_array($entry)) {
            $entry = [$entry];
        }

        return implode(",", $entry);
    }

    public static function staticRaw($sql, $params = [], $realRaw = false, $limit = false)
    {
        if ($limit) {
            $sql = str_replace("@LIMIT", "LIMIT {$limit}", $sql);
        }

        if ($realRaw) {
            $multipleQueries = explode(";#next", $sql);
            $r = [];
            foreach ($multipleQueries as $query) {
                $r = DB::select($query, $params);
            }
            return $r;
        } else {
            return DB::select($sql, $params);
        }
    }

    protected function raw($sql, $params = [], $realRaw = false, $limit = false)
    {
        return self::staticRaw($sql, $params, $realRaw, $limit);
    }

    protected function laraPaginate($sql, $itemsPerPage = 200, $useRaw = false)
    {
        $c = 1;
        $sql = str_replace(['SELECT', 'select'], '', $sql, $c);
        return DB::query()->select($sql)->simplePaginate($itemsPerPage);
    }

    /**
     * @param string $table
     * @param array $data
     * @param array $where
     *
     * @return int affected rows
     */
    protected function update($table, $data, $where = [])
    {
        if (is_numeric($where)) {
            $where = [
                'id' => $where,
            ];
        }

        return DB::table($table)->where($where)->update($data);
    }

    /**
     * @param string $table
     * @param array $where
     *
     * @return int affected rows
     */
    protected function delete($table, $where = [])
    {
        if (is_numeric($where)) {
            $where = [
                "id" => $where
            ];
        }

        return DB::table($table)->where($where)->delete();
    }


    /**
     * @param string $table
     * @param array $data
     *
     * @return int insert id
     */
    protected function insert($table, $data)
    {
        return DB::table($table)->insertGetId($data);
    }

    protected function multipleInsert($table, $data)
    {
        return DB::table($table)->insert($data);
    }

    /**
     * @param string $table
     * @param array $data
     * @param bool|int|array $id
     *
     * @param bool $multipleHosts
     * @return int item id
     */
    protected function updateOrInsert($table, $data, $id = false, $multipleHosts = false)
    {
        $dataUpdate = $data;
        $dataInsert = $data;

        if (isset($data["update"])) {
            $dataUpdate = $data["update"];
            $dataInsert = $data["update"];

            foreach ($data["insert"] as $key => $value) {
                $dataInsert[$key] = $value;
            }
        }

        if ($this->getById($id, $table)) {
            if ($multipleHosts) {
                $this->multipleHosts(null, null, function () use ($table, $dataUpdate, $id) {
                    $this->update($table, $dataUpdate, $id);
                });
            } else {
                $this->update($table, $dataUpdate, $id);
            }


            if (is_array($id)) {
                return @$id["id"];
            } else {
                return $id;
            }
        } else {
            if ($multipleHosts) {
                $res = $this->multipleHosts(null, null, function () use ($table, $dataInsert) {
                    $this->insert($table, $dataInsert);
                });
            } else {
                $res = $this->insert($table, $dataInsert);
            }

            changeConnection();

            return $res;
        }
    }

    protected function getById($id, $table, $row = false)
    {
        $res = DB::table($table)->where(["id" => $id])->get();

        if (count($res) > 0) {
            return $this->rawAsArray($res, $row);
        } else {
            return false;
        }
    }

    protected function paginate($sql, $limit, $offset, $params = [])
    {
        if ($limit == null) {
            $limit = 500;
        }

        if ($offset == null) {
            $offset = 0;
        }

        $limit = ($limit > 5000) ? 5000 : $limit;

        $result = DB::select("{$sql} LIMIT {$limit} OFFSET {$offset}", $params);

        return $result;
    }

    protected function get($table, $where = [], $columns = [], $row = false)
    {
        if (is_numeric($where)) {
            $where = [
                "id" => $where
            ];
        }

        $res = DB::table($table)->where($where)->get();

        if (count($res) == 0) {
            return false;
        }

        return $this->rawAsArray($res, $row);
    }

    protected function getHugeQuery($sql, $params = false)
    {
        $allRows = [];
        $limit = 100000;

        if (is_array($params)) {
            foreach ($params as $paramKey => $paramValue) {
                $sql = str_replace("@" . $paramKey, $paramValue, $sql);
            }
        }

        DB::disableQueryLog();
        DB::getPdo()->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

        $incrementRows = function ($offset) use ($limit, $sql) {
            $offsetLimit = $limit * ($offset);

            if (strpos($sql, "@LIMIT_CLAUSE") !== false) {
                $sql = str_replace("@LIMIT_CLAUSE", " LIMIT {$offsetLimit}, {$limit}", $sql);
            } else {
                $sql .= " LIMIT {$offsetLimit}, {$limit}";
            }

            return $this->raw($sql, [], true);
        };
        $i = 0;
        do {
            $rows = $incrementRows($i);
            if ($rows) {
                foreach ($rows as $row) {
                    $allRows[] = $row;
                }
            }
            $i++;
        } while (!(count($rows) < $limit || !$rows));

        if (count($allRows) == 0) {
            $allRows = false;
        }

        return $this->rawAsArray($allRows, false, false, true);
    }

    /**
     * @param $sql
     * @param array $params
     * @param bool $method
     * @return bool|mixed
     */
    protected function multipleHosts($sql, $params = [], $method = false)
    {
        $hosts = self::getHosts();

        $res = false;
        foreach ($hosts as $h) {
            changeConnection($h);
            if ($method instanceof \Closure) {
                $res = $method();
            } else {
                $res = $this->raw($sql, $params);
            }
        }

        return $res;
    }

    protected function multipleHostsInsert($table, $params = [], $method = false)
    {
        $hosts = self::getHosts();

        $res = false;
        foreach ($hosts as $h) {
            changeConnection($h);
            if ($method instanceof \Closure) {
                $res = $method();
            } else {
                $res = DB::table($table)->insert($params);
            }
        }

        return $res;
    }

    protected function multipleHostsInsertGetId($table, $params = [], $method = false)
    {
        $hosts = self::getHosts();

        $res = false;
        foreach ($hosts as $h) {
            changeConnection($h);
            if ($method instanceof \Closure) {
                $res = $method();
            } else {
                $res = DB::table($table)->insertGetId($params);
            }
        }

        return $res;
    }

}
