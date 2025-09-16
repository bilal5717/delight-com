<?php

namespace App\Http\Requests\Admin;

class ServiceSettingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'setting_key'   => ['required'],
            'setting_value' => ['max:16000000'],
        ];

        $rules['setting_key'][] = 'required';
        $rules['setting_value'][] = 'required';

        return $rules;
    }
}