<?php

namespace App\Http\Requests\Act;

use App\Http\Requests\Request;

class AddActRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => 'required|max:256',
            'intro'      => 'sometimes|max:2048',
            'name_format'      => 'required|max:64',
            'phone'      => 'required|max:12',
            'address'      => 'required',
            'time'      => 'required',
            'logo_id'      => 'required|between:1,100',
        ];
    }
}
