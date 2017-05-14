<?php

namespace App\Http\Requests\Act;

use App\Http\Requests\Request;

class ActRequest extends Request
{
    public function commonRules()
    {
        return [
            'title'         => 'max:256',
            'intro'      => 'max:2048',
            'name_format'      => 'max:64',
            'phone'      => 'max:12',
        ];
    }
}
