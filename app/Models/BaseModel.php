<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function scopeWithCertain($query, $relation, array $where, array $columns)
    {
        return $query->with([$relation => function ($query) use ($columns ,$where){
            $query->where($where)->select($columns);
        }]);
    }
}