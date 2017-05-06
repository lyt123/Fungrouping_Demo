<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TeamJoin
 *
 * @property int $id 自增长id
 * @property int $team_id 活动id
 * @property int $team_user_id
 * @property int $user_id 用户id
 * @property bool $expect_score 期待值
 * @property bool $satisfy_score 满意值
 * @property string $phone
 * @property bool $is_read 发布人是否已接收,0-否，1-是
 * @property bool $is_share
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TeamJoin whereExpectScore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TeamJoin whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TeamJoin whereIsRead($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TeamJoin whereIsShare($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TeamJoin wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TeamJoin whereSatisfyScore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TeamJoin whereTeamId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TeamJoin whereTeamUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TeamJoin whereUserId($value)
 * @mixin \Eloquent
 */
class TeamJoin extends Model
{
    protected $table = 'team_join';

    protected $guarded = ['id'];

    public $timestamps  = false;
}