<?php

namespace App\Http\Requests\Manage\People\Users;

use Diver\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

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
            'password'      => [
                'require' => 'required',
                'length.min' => 'min:8',
                'confirm' => 'confirmed',
            ],
            'password_confirmation' => [
                'require' => 'required',
            ],
        ];

        return $rules;
    }
}
