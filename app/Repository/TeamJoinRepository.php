<?php

namespace App\Repository;

use App\Models\TeamJoin;

class TeamJoinRepository extends BaseRepository
{
    const MODEL = TeamJoin::class;

    public static function getJoinInfo($condition = [])
    {
        $query_builder = static::setCondition($condition);

        return $query_builder
            ->with(['user' => function ($query) {
                $query->select('username', 'sex', 'id');
            }])
            ->get()
            ->toArray();
    }

    public static function teamInvited($condition = [])
    {
        $query_builder = static::setCondition($condition);

        return $query_builder
            ->with([
                'team' => function ($query) {
//                    $query->select('username', 'sex', 'id');
                }])
            ->get()
            ->toArray();
    }
}