<?php
namespace App\Repository;

class BaseRepository
{
    public static function read(array $param = [], $is_first = false)
    {
        $where = isset($param['where']) ? $param['where'] : [];
        $or_where = isset($param['or_where']) ? $param['or_where'] : [];
        $fields = isset($param['fields']) ? $param['fields'] : ['*'];

        try {
            if (isset($param['has_like']) || $or_where) {
                $data = static::setCondition($where, $or_where)->select($fields)->get()->toArray();
            } else {
                $data = static::query()->where($where)->select($fields)->get()->toArray();
            }
            return ($is_first && $data) ? $data[0] : $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function setCondition(array $where, array $or_where = [])
    {
        $object = static::query();
        foreach ($where as $key => $value) {
            if (is_array($value))
                $object = $object->where($key, $value[0], $value[1]);
            else
                $object = $object->where($key, $value);
        }

        if ($or_where) {
            foreach ($or_where as $key => $value) {
                if (is_array($value))
                    $object = $object->orWhere($key, $value[0], $value[1]);
                else
                    $object = $object->orWhere($key, $value);
            }
        }

        return $object;
    }

    public static function query()
    {
        return call_user_func(static::MODEL . '::query');
    }

    public static function store(array $data)
    {
        $instance = static::getInstance();
        try {
            $result = $instance->create($data);
        } catch (\Exception $e) {

            //插入失败，则删除已存在的一些图片之类的资源
            if ($instance->resourceFields) {
                $resource = get_data_in_array($data, $instance->resourceFields);
                if ($resource) remove_files($resource);
            }
            throw $e;
        }
        return $result;
    }

    public static function getInstance()
    {
        $instance = static::MODEL;
        return new $instance;
    }

    public static function updateData(array $data, $where)
    {
        try {
            if (empty($data))
                abort(422, trans('tip.nothing_update'));

            //update时默认的查找匹配字段为数据的id
            if (!is_array($where))
                $where = ['id' => $where];

            $object = static::setCondition($where);

            $instance = static::getInstance();
            //检测是否存在修改的资源字段
            if ($instance->resourceFields) {

                $new_resources = get_data_in_array($data, $instance->resourceFields);
                //如果需要，备份旧的资源字段
                if (isset($new_resources) && $new_resources)
                    $old_resources = $object->select(array_keys($new_resources))->first();
            }

            $result = $object->update($data);
            if (!$result)
                abort(422, trans('tip.nothing_update'));

            if (isset($old_resources) && count($old_resources))
                remove_files($old_resources->toArray());

            return $result;
        } catch (\Exception $e) {

            if (isset($new_resources) && $new_resources)
                remove_files($new_resources);
            if (env('APP_DEBUG'))
                throw $e;
            abort(500);
        }
    }

    public static function destroyData(array $where, $need_error = true)
    {
        $object = static::setCondition($where);
        $instance = static::getInstance();

        if ($instance->resourceFields)
            $resources = $object->select($instance->resourceFields)->get();
        if ((!$result = $object->forceDelete()) && $need_error) {
            abort(404, trans('tip.404'));
        }

        if (isset($resources) && count($resources)) {
            foreach ($resources->toArray() as $resource)
                remove_files($resource);
        }
        return $result;
    }

    public static function checkExist($where, $error_tip, $exist_abort = 0)
    {
        $object = static::setCondition($where);

        if (!count($object->get()) && $exist_abort == 0)
            abort(404, trans($error_tip));

        if (count($object->get()) && $exist_abort == 1)
            abort(404, trans($error_tip));
    }
}