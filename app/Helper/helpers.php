<?php

function d($data = 'haha'){
    if(gettype($data) == 'object'){
        dump($data->toArray());
    }else{
        dump($data);
    }
}

function f($data = 'hehe'){
    if(gettype($data) == 'object'){
        dd($data->toArray());
    }else{
        dd($data);
    }
}

if(! function_exists('success')) {

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
        $message = $message ?: trans('tips.200');

        $response = [
            'status'   => $status,
            'message' => $message
        ];

        if(!empty($data))
            $response['data'] = $data;

        if(config('app.debug')) {
            $response['sql'] = \App\Providers\AppServiceProvider::$sql_listen;
        }

        return response()->json($response);
    }
}

if(! function_exists('fail')) {

    function fail($inform = false, $status = 422, $position = 'ajax_error')
    {
        $data = [
            'status'  => $status,
            'inform' => $inform ?: trans('tips.fail')
        ];

        if(config('app.debug')) {
            $data['position'] = $position;
            $data['sql'] = \App\Providers\AppServiceProvider::$sql_listen;
        }

        //        if(env('APP_DEBUG', false))

        return response()->json($data, $status);
    }
}