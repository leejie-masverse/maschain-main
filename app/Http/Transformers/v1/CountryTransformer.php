<?php

namespace App\Http\Transformers\v1;

use Diver\Dataset\Country;

class CountryTransformer extends Transformer
{
    /**
     * Transform
     *
     * @param $token
     *
     * @return array
     */
    public function transform(Country $country)
    {
        $transformed = [
            'id' => $country->id,
            'name' => $country->name,
            'iso_code_alpha2' => $country->iso_code_alpha2,
            'iso_code_alpha3' => $country->iso_code_alpha3,
            'calling_code' => $country->calling_code,
            'address_format' => $country->address_format,
        ];

        return $this->touchTransformedData($transformed);
    }
}