<?php

namespace App\Http\Requests\v1\People\SystemUsers;

use Diver\Http\Requests\FormRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Src\Auth\Role;
use Src\People\User;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email'         => [
                'require' => 'required',
                'email'   => 'email',
                'unique'  => Rule::unique('users', 'email')
            ],
            'password'      => [
                'require'    => 'required',
                'length.min' => 'min:8',
            ],
            'full_name'     => [
                'require' => 'required',
            ],
            'phone'         => [
                'require' => 'required',
            ],
            'role'          => [
                'require' => 'required',
                'in'      => Rule::in($this->getAccessibleRoles()),
            ]
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
        $roles = array_intersect(Auth::user()->getAccessibleRoles(), Role::getSystemUserRoles());

        return $roles;
    }
}
