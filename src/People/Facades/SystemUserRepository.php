<?php

namespace Src\People\Facades;

use Illuminate\Support\Facades\Facade;

class SystemUserRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Src\People\Repositories\SystemUserRepository::class;
    }
}
