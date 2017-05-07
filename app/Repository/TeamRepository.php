<?php

namespace App\Repository;


use App\Models\Team;
use Illuminate\Database\Eloquent\Model;

class TeamRepository extends BaseRepository
{
    const MODEL = Team::class;

    public static function teamDetail($team_id)
    {
        //TODO 获取数据要关联team_join表
        $with_data = [
            'user' => function($query) {
                $query->select('head_path', 'username');
            }
        ];

        return static::getInstance()->where(['id' => $team_id])->with($with_data)->first();
    }
}