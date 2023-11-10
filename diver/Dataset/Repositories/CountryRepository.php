<?php

namespace Diver\Dataset\Repositories;

use Illuminate\Support\Facades\DB;
use Diver\Dataset\Country;

class CountryRepository
{
    /**
     * @param array $input
     * @return mixed
     */
    public function create(array $input)
    {
        $data = data_only($input, [
            'country.name',
            'country.iso_code_alpha2',
            'country.iso_code_alpha3',
            'country.calling_code',
            'country.ep_code',
            'country.address_format',
        ]);

        return DB::transaction(function () use ($data) {
            $country = Country::create($data['country']);

            return $country;
        });
    }

    public function update(Country $country, array $input)
    {
        $data = data_only($input, [
            'country.name',
            'country.iso_code_alpha2',
            'country.iso_code_alpha3',
            'country.ep_code',
            'country.calling_code',
            'country.address_format',
        ]);

        return DB::transaction(function () use ($country, $data) {
            $country->update($data['country']);

            return $country->refresh();
        });
    }

    public function delete(Country $country, $forceDelete = false)
    {
        return DB::transaction(function () use ($country, $forceDelete) {
            return $forceDelete ? $country->forceDelete() : $country->delete();
        });
    }
}
