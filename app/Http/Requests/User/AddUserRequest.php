<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class AddUserRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|between:5,12|alpha_num',
            'sex' => 'in:f,m',
        ];
    }
}