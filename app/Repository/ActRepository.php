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
}