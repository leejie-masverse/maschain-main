<?php

namespace App\Http\Requests\v1\Auth\Token;

use Diver\Http\Requests\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'phone_prefix' => [
                'required' => 'required',
            ],
            'phone_number' => [
                'required' => 'required',
            ],
            'password' => [
                'required' => 'required',
            ],
        ];

        return $rules;
    }
}
