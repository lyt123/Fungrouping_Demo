<?php

namespace App\Http\Requests\Act;

class UpdateActRequest extends ActRequest
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
        $rules = [
            'id'        => 'required|numeric',
            'title'         => 'required',
            'intro'      => 'sometimes',
            'phone'      => 'required',
        ];

        return merge_rules($rules, $this->commonRules());
    }
}