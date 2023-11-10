<?php

namespace App\Http\Requests\v1\Auth\User;

use Diver\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
                'email' => 'email',
                'unique' => Rule::unique('users', 'email')->ignore(Auth::user()->id),
            ],
            'full_name' => [
                'require' => 'required',
            ],
            'phone' => [
                'require' => 'required',
            ],
        ];

        return $rules;
    }
}
