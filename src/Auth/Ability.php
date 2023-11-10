<?php

namespace Src\Auth;

use Diver\Database\Eloquent\Traits\Model;
use Silber\Bouncer\Database\Ability as BaseAbility;

class Ability extends BaseAbility
{
    use Model;

    CONST MANAGE_ROOTS = 'manage roots';
    CONST MANAGE_ADMINISTRATORS = 'manage administrators';
    CONST MANAGE_USERS = 'manage users';

    public static function getAllAdminAbilities()
    {
        return [
            self::MANAGE_ROOTS,
            self::MANAGE_ADMINISTRATORS,
//            self::MANAGE_USERS,
        ];
    }
}
