<?php

namespace Src\Auth\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Src\People\AssociateUser;
use Src\People\SystemUser;
use Src\People\User;

class UserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
//        $credentials['status'] = User::STATUS_ACTIVE;

        /**
         * @var \Src\People\User $user
         */
        $user = parent::retrieveByCredentials($credentials);
        if ( ! $user) {
            return;
        }

        return $user;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $user = parent::retrieveById($identifier);
        if ( ! $user) {
            return;
        }

        return $user;
    }
}
