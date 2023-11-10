<?php

namespace Diver\Http\Transformers;

use Diver\Field\Address;

abstract class AddressTransformer extends Transformer
{
    /**
     * Excludes
     *
     * @var array
     */
    protected $excludes = [];

    /**
     * Transform
     *
     * @param \Diver\Field\Address $address
     *
     * @return array
     */
    public function transform(Address $address)
    {
        $subdivision = optional($address->subdivision);
        $country = optional($address->country);

        $transformed = [
            'line' => $address->address_line,
            'street1' => $address->street1,
            'street2' => $address->street2,
            'postal_code' => $address->postal_code,
            'city' => $address->city,
            'city_id' => $address->city_id,
            'subdivision' => $subdivision->name,
            'subdivision_id' => $subdivision->id,
            'country' => $country->name,
            'country_id' => $country->id,
            'latitude' => $address->latitude,
            'longitude' => $address->longitude,
        ];

        return array_except($transformed, $this->excludes);
    }
}