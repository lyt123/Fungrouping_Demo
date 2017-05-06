<?php

namespace App\Repository;

use \App\Models\User;

class UserRepository extends BaseRepository
{
    const MODEL = User::class;

    public static function validatePassword($password, $hash_password)
    {
        return password_verify($password, $hash_password);
    }

    public static function testCode($code, $session_code, $send_time)
    {
        if($session_code == $code && (time() - $send_time <= 3000)) {
            return true;
        }
        return false;
    }
}