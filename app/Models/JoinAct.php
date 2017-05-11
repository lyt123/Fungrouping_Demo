<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinAct extends Model
{
    protected $table = 'joinact';

    protected $guarded = ['id'];

    //    const CREATED_AT = 'ctime';
    public $timestamps  = false;


}