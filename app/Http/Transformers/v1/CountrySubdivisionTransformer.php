<?php


namespace App\Http\Transformers\v1;

use Diver\Dataset\CountrySubdivision;

class CountrySubdivisionTransformer extends Transformer
{
    /**
     * Transform
     *
     * @param $token
     *
     * @return array
     */
    public function transform(CountrySubdivision $countrySubdivision)
    {
        $transformed = [
            'id' => $countrySubdivision->id,
            'name' => $countrySubdivision->name,
            'country' => $countrySubdivision->country,
        ];

        return $this->touchTransformedData($transformed);
    }
}
