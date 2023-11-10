<?php


namespace App\Http\Requests\Profile;


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
            'username'     => [
                'require' => 'required',
            ],
            'email'     => [
                'require' => 'required',
                'email' => 'email',
                'unique' => Rule::unique('users', 'email')->ignore(Auth::id()),
            ],
        ];

        return $rules;
    }
}
