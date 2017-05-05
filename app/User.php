<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    
    public static function checkLogin($where)
    {

    }
}
