<?php

namespace App\Http\Requests\Manage\People\Users;

use Illuminate\Validation\Rule;

class UpdateRequest extends StoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = $this->route('id');

        $rules = parent::rules();
        $rules['email']['unique']->ignore($userId);
        //$rules['phone_number']['unique']->ignore($userId);

        unset($rules['password'], $rules['password_confirmation']);

        return $rules;
    }
}
