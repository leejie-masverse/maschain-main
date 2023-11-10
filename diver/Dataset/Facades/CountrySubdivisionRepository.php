<?php

namespace Diver\Dataset\Facades;

use Illuminate\Support\Facades\Facade;

class CountrySubdivisionRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Diver\Dataset\Repositories\CountrySubdivisionRepository::class;
    }

}