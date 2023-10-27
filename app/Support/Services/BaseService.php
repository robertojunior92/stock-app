<?php
/**
 * Created by PhpStorm.
 * User: MaiaVinicius
 * Date: 29/04/18
 * Time: 12:31
 */

namespace App\Support\Services;

abstract class BaseService
{
    protected $repository;

    /**
     * @param string $name
     * @return string
     */
    public function cleanName($name)
    {
        return str_replace('|', '-', $name);
    }
}