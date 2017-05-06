<?php

namespace App\Repository;


use App\Models\Team;
use Illuminate\Database\Eloquent\Model;

class TeamRepository extends BaseRepository
{
    const MODEL = Team::class;

    public static function teamList($data)
    {
        Model::
        $where = [];
        if($data['srh_string'])
            $where['has_like'] = ['title', 'like', "%{$data['srh_string']}%"];
        if($data['city_id']){
            $where['where'] = ['city_id' => $data['city_id']];
        }
    }
}