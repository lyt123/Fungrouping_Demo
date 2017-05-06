<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    protected $guarded = ['id'];

    public $timestamps  = false;
    
    public static function checkLogin($where)
    {

    }
}
