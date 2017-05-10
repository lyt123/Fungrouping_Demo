<?php

namespace App\Repository;


use App\Models\Team;

class TeamRepository extends BaseRepository
{
    const MODEL = Team::class;

    public static function teamList($title)
    {
        $with_data = [
            'user' => function ($query) {
//                $query->select('head_path');
            }
        ];

        return static::setCondition(['title' => ['like', '%' . $title . '%']])->with($with_data)->get();
    }

    public static function teamDetail($team_id)
    {
        //TODO 获取数据要关联team_join表
        $with_data = [
            'user' => function ($query) {
                //                $query->select('head_path', 'username');
            },
            'team_join' => function ($query) {
                //                $query->select('phone');
            }
        ];

        return static::setCondition(['id' => $team_id])->with($with_data)->first()->toArray();
    }
}