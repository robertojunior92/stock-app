<?php
/**
 * Created by PhpStorm.
 * User: Desenvolvimento
 * Date: 2018-05-03
 * Time: 14:58
 */

namespace App\Support\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\LicenseRepository;
use Hashids\Hashids;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\AdminController;
use App\Services\AdminService;


abstract class BaseController extends Controller
{
    function getHashDatabase($databaseInput, $setDatabase = false, $allowNumeric = false)
    {
        if(is_numeric($databaseInput) && $allowNumeric){
            if($this->validateDatabaseInput($databaseInput) === false)
            {
                throw new \Exception('error licenca inexistente');
            }
            setClientDatabase($databaseInput);
            return $databaseInput;
        }
        $encryptKey = config('app.app_salt');

        $hashids = new Hashids($encryptKey);
        $clientId = false;

        if ($databaseInput) {
            $decoded = $hashids->decode($databaseInput);

            if ($decoded) {
                $clientId = $decoded[0];

                if ($setDatabase) {
                    setClientDatabase($clientId);
                }
            }
        } else {
            $clientId = false;
        }

        return $clientId;
    }

    private function validateDatabaseInput($databaseInput)
    {
        $lic = new LicenseRepository();
        $list = $lic->getLicenseById($databaseInput);
        if(empty($list))
        {
            return false;
        }
    }

    protected function compressHtml($buffer)
    {
        if (strpos($buffer, '<pre>') !== false) {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/" => '<?php ',
                "/\r/" => '',
                "/>\n</" => '><',
                "/>\s+\n</" => '><',
                "/>\n\s+</" => '><',
            );
        } else {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/" => '<?php ',
                "/\n([\S])/" => '$1',
                "/\r/" => '',
                "/\n/" => '',
                "/\t/" => '',
                "/ +/" => ' ',
            );
        }
        $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);

        return $buffer;

    }
}