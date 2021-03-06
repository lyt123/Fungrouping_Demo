<?php

namespace App\Http\Requests\Act;

class AddActRequest extends ActRequest
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
        $rules = [
            'title'         => 'required',
            'intro'      => 'sometimes',
            'name_format'      => 'required',
            'phone'      => 'required',
            'address'      => 'required',
            'time'      => 'required',
            'logo_id'      => 'required',
        ];

        return merge_rules($rules, $this->commonRules());
    }
}
