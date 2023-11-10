<?php

namespace App\Http\Requests\v1\Auth\User;

use Diver\Http\Requests\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'password' => [
                'require' => 'required',
                'min'     => 'min:8',
            ],
        ];

        return $rules;
    }
}
