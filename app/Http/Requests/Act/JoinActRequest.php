<?php

namespace App\Http\Requests\Act;

use App\Http\Requests\Request;

class JoinActRequest extends Request
{
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
            'name_format' => 'required|max:16',
            'id' => 'required|numeric'
        ];
    }
}