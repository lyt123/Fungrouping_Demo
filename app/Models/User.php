<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $phone
 * @property string $password
 * @property string $username
 * @property string $head_path
 * @property string $sex
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereHeadPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Model
{
    protected $table = 'user';

    protected $guarded = ['id'];

    public $timestamps  = false;

    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }

    public function userintro()
    {
        return $this->hasOne(UserIntro::class, 'userid');
    }

    public function user_team()
    {
        return $this->belongsToMany(Team::class, 'team_join');
    }
    
}
