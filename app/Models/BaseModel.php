<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function scopeWithCertain($query, $relation, $columns = [], $where = [])
    {
        return $query->with([$relation => function ($query) use ($columns, $where) {
            if ($where)
                $query->where($where);
            if ($columns) {
                $query->select($columns);
            } else {
                $query->select();

            }
        }]);
    }
}