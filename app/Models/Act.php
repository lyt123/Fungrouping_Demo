<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    protected $table = 'act';

    protected $guarded = ['id'];

//    const CREATED_AT = 'ctime';
    public $timestamps  = false;


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

}