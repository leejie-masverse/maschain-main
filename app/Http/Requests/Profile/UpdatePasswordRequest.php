<?php


namespace App\Http\Requests\Profile;


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
                'confirm' => 'confirmed'
            ],
            'password_confirmation' => [
                'require' => 'required',
            ],
        ];

        return $rules;
    }
}
