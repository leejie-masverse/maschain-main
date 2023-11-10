<?php


namespace Src\Auth\Facades;

use Illuminate\Support\Facades\Facade;

class PasswordResetRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Src\Auth\Repositories\PasswordResetRepository::class;
    }

}
