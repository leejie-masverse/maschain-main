<?php

namespace Diver\Dataset\Repositories;

use Diver\Dataset\City;
use Illuminate\Support\Facades\DB;

class CityRepository
{
    public function create(array $input)
    {
        $data = data_only($input, [
            'city.name',
            'city.subdivision_id',
            'city.country_id'
        ]);

        return DB::transaction(function () use ($data) {
            $city = City::create($data['city']);

            return $city;
        });
    }

    public function update(City $city, array $input)
    {
        $data = data_only($input, [
            'city.name',
            'city.subdivision_id',
            'city.country_id'
        ]);

        return DB::transaction(function () use ($city, $data) {
            $city->update($data['city']);

            return $city->refresh();
        });
    }

    public function delete(City $city, $forceDelete = false)
    {
        return DB::transaction(function () use ($city, $forceDelete) {
            return $forceDelete ? $city->forceDelete() : $city->delete();
        });
    }
}
