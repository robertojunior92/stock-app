<?php

use App\Services\AdminService;

function getLaravelSessionDetails()
{
    $adminService = new AdminService();

    $userID = session('id');

    return $adminService->getUserInfo($userID);
}
