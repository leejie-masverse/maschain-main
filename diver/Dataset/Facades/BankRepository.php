<?php

namespace Diver\Dataset\Facades;

use Illuminate\Support\Facades\Facade;

class BankRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Diver\Dataset\Repositories\BankRepository::class;
    }

}
