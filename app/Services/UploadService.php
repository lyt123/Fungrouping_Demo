<?php

namespace App\Services;

use Illuminate\Http\Request;

class UploadService
{

    /**
     * Description :
     * Author : Benson
     *
     * @param Request $request
     * @param array $config
     * @param bool $is_need 是否必须上传文件
     * @return string
     */
    public static function upload(Request $request, $config = [
        'name' => 'head',
        'position' => 'upload/img/user_head/',
    ], $is_need = true)
    {
        isset($config['allow_extensions']) ?: $config['allow_extensions'] = ['jpg', 'png', 'gif'];
        isset($config['size']) ?: $config['size'] = 2097152;

        if(!$request->hasFile($config['name'])) {
            if($is_need) {
                abort(404, trans('tip.empty_file'));
            }
            return null;
        }

        $file = $request->file($config['name']);

        if(!$file->isValid())
            abort(500);

        //检查文件类型
        $extension = $file->getClientOriginalExtension();

        if ($extension && !in_array($extension, $config['allow_extensions']))
            abort(403, trans('tip.refuse_extension'));

        //限制文件上传大小
        if ($file->getSize() > $config['size'])
            abort(403, trans('tip.exceed_size'));

        //归类存放文件
        $file_name = session()->get('user.id') . str_random(8) . '.' . $extension;
        $request->file($config['name'])->move($config['position'], $file_name);
        
        return $config['position'] . $file_name;
    }
}