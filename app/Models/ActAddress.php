<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActAddress extends Model
{
    protected $table = 'act_address';

    protected $guarded = ['id'];

    //    const CREATED_AT = 'ctime';
    public $timestamps  = false;


    public static function addAddress($act_id, $address, $single_address)
    {
        for ($i = 0; $i < count($address); $i++) {
            $addresslist[] = array(
                'actid' => $act_id,
                'address' => $address[$i],
                'choose' => 0
            );
        };

        $addresslist[0]['choose'] = $single_address ? 1 : 0;

        static::insert($addresslist);
    }

    public static function response($address)
    {
        $addressids = explode('-', $address);

        foreach($addressids as $addressid){
            static::where('id', $addressid)->increment('votes');
        }
    }
}
