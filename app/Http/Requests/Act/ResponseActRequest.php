<?php

namespace App\Http\Requests\Act;

use App\Http\Requests\Request;

class ResponseActRequest extends Request
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
            'actid'         => 'required|integer',
            'time_voted'      => 'required',
            'address_voted'      => 'required',
            'name_format'      => 'sometimes|max:64',
        ];
    }
}