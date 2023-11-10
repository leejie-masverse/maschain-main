<?php

namespace App\Http\Transformers\v1;

use Src\People\SystemUser;

class SystemUserTransformer extends Transformer
{
    /**
     * Transform
     *
     * @param \Src\People\SystemUser $user
     *
     * @return array
     */
    public function transform(SystemUser $user)
    {
        $transformed = [
            'id'         => $user->id,
            'type'       => $user->type,
            'email'      => $user->email,
            'full_name'  => $user->profile->full_name,
            'phone'      => $user->phone->phone,
            'role'       => $user->role->name,
            'status'     => $user->status,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        return $this->touchTransformedData($transformed);
    }
}
