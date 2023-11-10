<?php

namespace Diver\Dataset\Repositories;

use Illuminate\Support\Facades\DB;
use Diver\Dataset\CountrySubdivision;

class CountrySubdivisionRepository
{
    public function create(array $input)
    {
        $data = data_only($input, [
            'country_subdivision.name',
            'country_subdivision.iso_code',
            'country_subdivision.ep_code',
            'country_subdivision.category',
            'country_subdivision.country_id'
        ]);

        return DB::transaction(function () use ($data) {
            $countrySubdivision = CountrySubdivision::create($data['country_subdivision']);

            return $countrySubdivision;
        });
    }

    public function update(CountrySubdivision $countrySubdivision, array $input)
    {
        $data = data_only($input, [
            'country_subdivision.name',
            'country_subdivision.ep_code',
            'country_subdivision.iso_code',
            'country_subdivision.category',
            'country_subdivision.country_id'
        ]);

        return DB::transaction(function () use ($countrySubdivision, $data) {
            $countrySubdivision->update($data['country_subdivision']);

            return $countrySubdivision->refresh();
        });
    }

    public function delete(CountrySubdivision $countrySubdivision, $forceDelete = false)
    {
        return DB::transaction(function () use ($countrySubdivision, $forceDelete) {
            return $forceDelete ? $countrySubdivision->forceDelete() : $countrySubdivision->delete();
        });
    }
}
