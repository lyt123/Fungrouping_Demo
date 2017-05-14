<?php

function d($data1 = 'haha', $data2 = '', $data3 = '', $data4 = '')
{
    if (gettype($data1) == 'object') {
        dump($data1->toArray());
    } else {
        dump($data1);
    }
}

function f($data = 'hehe')
{
    if (gettype($data) == 'object') {
        dd($data->toArray());
    } else {
        dd($data);
    }
}

if (!function_exists('success')) {

    /**
     * Description : json返回请求成功相关信息
     * Author : Benson
     *
     * @param array $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function success($data = [], $message = '', $status = 200)
    {
        $message = $message ?: trans('tip.200');

        $response = [
            'status' => $status,
            'message' => $message
        ];

        if (!empty($data))
            $response['data'] = $data;

        if (config('app.debug')) {
            $response['sql'] = \App\Providers\AppServiceProvider::$sql_listen;
        }

        return response()->json($response);
    }
}

if (!function_exists('fail')) {

    function fail($inform = false, $status = 422, $position = 'ajax_error')
    {
        $data = [
            'status' => $status,
            'inform' => $inform ?: trans('tip.fail')
        ];

        if (config('app.debug')) {
            $data['position'] = $position;
            $data['sql'] = \App\Providers\AppServiceProvider::$sql_listen;
        }

        //        if(env('APP_DEBUG', false))

        return response()->json($data, $status);
    }
}

if (!function_exists('get_data_in_array')) {

    /**
     * Description : 获取数组中指定值组合
     * Auth : Shelter
     *
     * @param array $data
     * @param array $keys
     */
    function get_data_in_array(array $data, array $keys)
    {
        $result = array();
        foreach ($keys as $key) {
            if (isset($data[$key])) $result[$key] = $data[$key];
        }
        return $result;
    }
}

if (!function_exists('remove_file')) {

    /**
     * Description : remove_files
     * Auth : Shelter
     *
     * @param $paths
     */
    function remove_files($paths)
    {
        if (is_array($paths)) {
            foreach ($paths as $path) {
                if (file_exists($path)) unlink($path);
            }
        } else {
            if (file_exists($paths)) unlink($paths);
        }
    }
}

if (!function_exists('array_filter_except')) {

    /**
     * Description : 过滤掉数组中空值，除了指定允许为空的值
     * Auth : Shelter
     *
     * @param array $data
     * @param array $filed
     */
    function array_filter_except(array &$data/*, array $filed = []*/)
    {
        foreach ($data as $key => $value) {
            //null表明前端并没有发送该字段,即使发送值为null也会被识别成字符,直接unset掉
            if (is_null($value)) {
                unset($data[$key]);
                continue;
            }
            //为''值表明前端发送空值过来，判断不在in_array里面表示该字段不允许为空值，需要添加sometimes规则，否则报错
            //            if(!in_array($key, $filed) && $value === '') {
            //                abort(422, trans('tips.validation_tip'));
            //            }
        }
    }
}

/**
 * 合并验证规则
 */
if(! function_exists('merge_rules')) {

    function merge_rules($rules, $common_rules) {

        foreach($rules as $key => $value) {

            if(isset($common_rules[$key])) {
                if(is_array($value)) {

                    if(is_array($common_rules[$key])) {
                        $common_rules[$key] = array_merge($common_rules[$key], $value);
                    }
                    else {
                        $value[] = $common_rules[$key];
                        $common_rules[$key] = $value;
                    }
                    continue;
                }
                else {
                    if(is_array($common_rules[$key])) {
                        $common_rules[$key][] = $value;
                        continue;
                    }
                }
                $common_rules[$key] = $value.'|'.$common_rules[$key];
                continue;
            }
            $common_rules[$key] = $value;
        }
        return $common_rules;
    }
}
