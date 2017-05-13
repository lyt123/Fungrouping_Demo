<?php

namespace App\Repository;


use App\Models\Act;
use Illuminate\Support\Facades\DB;

class ActRepository extends BaseRepository
{
    const MODEL = Act::class;

    public static function myAct($user_id, $limit)
    {
        //        userid actid joinact power<>0 ActTime starttime, timelast choose=1 and actid= act title intro
        $with_data = [
            'act_time' => function ($query) {
                $query->where(['choose' => 1])->select('starttime', 'actid');
            },
            'act_user' => function ($query) {
                $query->where(['power' => 0])->select('is_share');
            }
        ];


        return static::setCondition(['userid' => $user_id])
//                        ->with($with_data)
            ->withCertain('act_time', ['choose' => 1], ['starttime', 'actid', 'id'])
            ->withCertain('act_user', ['power' => 0], ['is_share'])
            ->select('id', 'title', 'join_num', 'ctime', 'vote_state')
            ->paginate($limit)
            ->toArray();
    }

    public static function detail($act_id, $user_id)
    {
        return static::setCondition(['id' => $act_id])
            ->withCertain('user', ['username', 'id'])
            ->withCertain('act_time')
            ->withCertain('act_user', [], ['userid' => $user_id])
            ->first(['id', 'title', 'ctime', 'join_num', 'intro', 'phone', 'logo_id', 'name_format', 'userid']);
    }

    public static function timeVote($act_id)
    {
        return static::setCondition(['id'=> $act_id])
            ->withCertain('act_time', ['actid', 'id', 'starttime', 'votes'])
            ->withCertain('act_user')
            ->first(['id']);
    }
}