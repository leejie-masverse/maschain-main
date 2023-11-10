<?php

namespace App\Http\Requests\v1\People\SystemUsers;

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

        unset($rules['password']);
        unset($rules['role']);

        return $rules;
    }
}
