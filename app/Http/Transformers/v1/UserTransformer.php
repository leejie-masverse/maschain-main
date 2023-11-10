<?php

namespace App\Http\Transformers\v1;

use Diver\Http\Transformers\Transformer;
use Src\People\SystemUser;
use Src\People\User;

class UserTransformer extends Transformer
{
    /**
     * Transform
     *
     * @param \Src\People\User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        if ($user instanceof SystemUser) {
            return (new SystemUserTransformer)->transform($user);
        }

        return (new SystemUserTransformer)->transform($user);
    }
}
