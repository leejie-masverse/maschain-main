<?php

namespace App\Http\Requests\Manage\People\Admins;

use Diver\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Failed Message
     *
     * @var string
     */
    protected $failedMessage = "Admin couldn't be created.";

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'portrait' => [
                'nullable' => 'nullable',
                'image'  => 'image',
                'max' => 'max:8192'
            ],

            'full_name' => [
                'require' => 'required',
            ],

            'email' => [
                'require' => 'required',
                'email'   => 'email',
                'unique'  => Rule::unique('users', 'email'),
            ],

            'role' => [
                'require' => 'required',
                'in'      => Rule::in($this->getAccessibleRoles()),
            ],

            'password' => [
                'require'    => 'required',
                'length.min' => 'min:8',
                'confirm'    => 'confirmed',
            ],

            'password_confirmation' => [
                'require' => 'required',
            ],
        ];

        return $rules;
    }

    /**
     * Get accessible roles
     *
     * @return array
     */
    protected function getAccessibleRoles()
    {
        $roles = auth()->user()->getAccessibleRoles();

        return $roles;
    }
}
