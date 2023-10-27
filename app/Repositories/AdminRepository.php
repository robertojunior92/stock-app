<?php

namespace App\Repositories;


use App\Support\Repositories\BaseRepository;

class AdminRepository extends BaseRepository
{
    public function login($email, $password)
    {
        $sql =
            "SELECT *
            FROM users
            WHERE email = ? AND
                  password = SHA1(?)";

        $params = [
            $email,
            $password
        ];

        $result = $this->raw($sql, $params);

        return $this->rawAsArray($result, true);
    }

    public function getUserInfo($userID = false)
    {

        $result = $this->raw( "SELECT *
                                    FROM users u
                                    WHERE u.id = ?", [$userID]);

        return $this->rawAsArray($result);
    }
}
