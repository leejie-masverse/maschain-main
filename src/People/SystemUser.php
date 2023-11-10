<?php

namespace Src\People;

use Src\Auth\Role;

class SystemUser extends User
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'profile',
        'roles',
    ];

}
