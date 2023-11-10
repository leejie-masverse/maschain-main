<?php

namespace App\Http\Transformers\v1;

use Diver\Dataset\Country;
use Diver\Dataset\CountrySubdivision;
use Diver\Http\Transformers\Transformer as BaseTransformer;

abstract class Transformer extends BaseTransformer
{
    /**
     * Casts
     *
     * @var array
     */
    protected $casts = [
        Country::class => 'castCountry',
        CountrySubdivision::class => 'castCountrySubdivision',
    ];

    /**
     * Cast country
     *
     * @param Country $country
     * @return array
     */
    protected function castCountry(Country $country)
    {
        return (new CountryTransformer)->transform($country);
    }

    /**
     * Cast country subdivision
     *
     * @param CountrySubdivision $division
     *
     * @return array
     */
    protected function castCountrySubdivision(CountrySubdivision $division)
    {
        return (new CountrySubdivisionTransformer)->transform($division);
    }
}
