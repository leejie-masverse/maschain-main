<?php

namespace Src\Auth;

use Diver\Database\Eloquent\Traits\Model;
use Silber\Bouncer\Database\Role as BaseRole;

class Role extends BaseRole
{
    use Model;

    /**
     * Roles enum
     */
    CONST SYSTEM_ROOT = 'system root';
    CONST SYSTEM_ADMINISTRATOR = 'system administrator';
    CONST SYSTEM_USER = 'system user';

    /**
     * Get system user roles
     *
     * @return array
     */
    public static function getSystemUserRoles()
    {
        return [
            static::SYSTEM_ROOT,
            static::SYSTEM_ADMINISTRATOR,
            static::SYSTEM_USER,
        ];
    }

    /**
     * Get admin roles
     *
     */
    public static function getAdminRoles()
    {
        return Role::whereIn('name', [
            static::SYSTEM_ROOT,
            static::SYSTEM_ADMINISTRATOR,
        ])->get();
    }
}
