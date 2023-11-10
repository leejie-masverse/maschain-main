<?php

namespace Diver\Dataset\Facades;

use Illuminate\Support\Facades\Facade;

class CountryRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Diver\Dataset\Repositories\CountryRepository::class;
    }

}