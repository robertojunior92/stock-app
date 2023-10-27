<?php
/**
 * Created by PhpStorm.
 * User: Feegow
 * Date: 25/05/2018
 * Time: 14:33
 */

namespace App\Services;


use App\Support\Services\BaseService;
use Illuminate\Http\Request;
use App\Repositories\AdminRepository;

class AdminService extends BaseService
{
    private $adminRepository;

    function __construct()
    {
        $this->adminRepository = new AdminRepository();
    }

    public function login($email, $password)
    {
        $result = $this->adminRepository->login($email, $password);

        if ($result) {
            session([
                "id" => $result['id']
            ]);
            return true;
        } else return false;
    }

    public function getUserInfo($userID)
    {
        return $this->adminRepository->getUserInfo($userID);
    }
    public function loginByUserId($userId)
    {
        session([
            "id" => $userId,
        ]);
        return true;
    }
}
