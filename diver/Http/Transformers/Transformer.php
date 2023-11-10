<?php

namespace Diver\Http\Transformers;

use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

abstract class Transformer extends TransformerAbstract
{
    /**
     * Cast array
     *
     * @var array
     */
    protected $casts = [];

    protected function getCasts()
    {
        $casts = [
            Carbon::class => 'castCarbonToIso8601String',
        ];

        return array_merge($casts, $this->casts);
    }

    /**
     * Final touching and clean up transformed data
     *
     * @param array $data
     *
     * @return array
     */
    public function touchTransformedData(array $data)
    {
        $castMaps = $this->getCasts();

        $touched = collect($data)->map(function ($value) use ($castMaps) {
            if ( ! is_object($value)) {
                return $value;
            }

            $valueClass = get_class($value);

            if ( ! isset($castMaps[$valueClass])) {
                return $value;
            }

            return $this->{$castMaps[$valueClass]}($value);
        });

        return $touched->toArray();
    }

    /**
     * Cast carbon to ISO 8601 string
     *
     * @param \Carbon\Carbon $value
     *
     * @return string
     */
    protected function castCarbonToIso8601String(Carbon $value)
    {
        return $value->toIso8601String();
    }
}