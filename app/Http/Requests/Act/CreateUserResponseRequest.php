<?php

namespace App\Http\Requests\Act;

use App\Http\Requests\Request;

class CreateUserResponseRequest extends Request
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
            'id' => 'numeric|required',
            'timeid' => 'numeric',
            'addressid' => 'sometimes|numeric',
        ];
    }
}