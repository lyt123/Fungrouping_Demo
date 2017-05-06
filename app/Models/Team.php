<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Team
 *
 * @property int $id 自增长id
 * @property string $title 活动主题
 * @property string $intro 活动详情
 * @property string $ctime 创建时间
 * @property int $user_id 创建人id
 * @property string $phone 发布人电话
 * @property int $num_join 参加人数
 * @property int $num_max 最多参加人数
 * @property int $starttime 活动开始时间
 * @property float $timelast 活动时长
 * @property string $address 活动地点
 * @property string $group_num QQ讨论组号码
 * @property string $cover 活动封面图
 * @property int $logo_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereCover($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereCtime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereGroupNum($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereIntro($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereLogoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereNumJoin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereNumMax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereStarttime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereTimelast($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Team whereUserId($value)
 * @mixin \Eloquent
 */
class Team extends Model
{
    protected $table = 'team';

    protected $guarded = ['id'];

    public $timestamps  = false;

}