<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserIntro extends Model
{
    protected $table = 'user_intro';

    protected $guarded = ['id'];

    public $timestamps  = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}