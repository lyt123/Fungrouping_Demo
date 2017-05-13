<?php

namespace App\Models;

class Act extends BaseModel
{
    protected $table = 'act';

    protected $guarded = ['id'];

//    const CREATED_AT = 'ctime';
    public $timestamps  = false;


    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function act_time()
    {
        return $this->hasMany(ActTime::class, 'actid');
    }

    public function act_user()
    {
        return $this->belongsToMany(User::class, 'joinact', 'actid', 'userid');
    }

}