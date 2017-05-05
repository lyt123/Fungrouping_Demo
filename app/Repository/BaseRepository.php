<?php
namespace App\Repository;

class BaseRepository
{
    public function read(array $param = [], $is_first = false)
    {
        $where = isset($param['where']) ? $param['where'] : [];
        $or_where = isset($param['or_where']) ? $param['or_where'] : [];
        $fields = isset($param['fields']) ? $param['fields'] : ['*'];

        try {
            if(isset($param['has_like']) || $or_where) {
                $data = $this->setCondition($where, $or_where)->select($fields)->get()->toArray();
            }
            else {
                $data = $this->query()->where($where)->select($fields)->get()->toArray();
            }
            return ($is_first && $data) ? $data[0] :$data;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    public function setCondition(array $where, array $or_where = [])
    {
        $object = $this->query();
        foreach($where as $key => $value) {
            if(is_array($value))
                $object = $object->where($key, $value[0], $value[1]);
            else
                $object = $object->where($key, $value);
        }

        if($or_where) {
            foreach($or_where as $key => $value) {
                if(is_array($value))
                    $object = $object->orWhere($key, $value[0], $value[1]);
                else
                    $object = $object->orWhere($key, $value);
            }
        }

        return $object;
    }

    public function query()
    {
        return call_user_func(static::MODEL.'::query');
    }
}