<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActTime extends Model
{
    protected $table = 'act_time';

    protected $guarded = ['id'];

    //    const CREATED_AT = 'ctime';
    public $timestamps  = false;



    public static function addTime($act_id, $time, $single_time)
    {
        for ($i = 0; $i < count($time); $i++) {
            $time[$i]['actid'] = $act_id;
            $time[$i]['starttime'] = strtotime($time[$i]['starttime']);
            $time[$i]['choose'] = 0;
        };
        $time[0]['choose'] = $single_time ? 1 : 0;

        static::insert($time);
    }

    public static function response($time)
    {
        $timeids = explode('-', $time);

        foreach($timeids as $timeid){
            static::where('id', $timeid)->increment('votes');
        }
    }
}
