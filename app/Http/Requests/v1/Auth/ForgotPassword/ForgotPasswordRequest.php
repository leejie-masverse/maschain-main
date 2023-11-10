<?php

namespace App\Http\Requests\v1\Auth\ForgotPassword;

use Diver\Http\Requests\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => [
                'require' => 'required',
            ],
        ];

        return $rules;
    }
}
