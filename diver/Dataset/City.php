<?php

namespace Diver\Dataset;

class City extends Dataset
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'dataset_cities';

	/**
	 * Belongs to country
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function country()
	{
		return $this->belongsTo(Country::class, 'country_id');
	}

	/**
	 * Belongs to subdivision
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function subdivision()
	{
		return $this->belongsTo(CountrySubdivision::class, 'subdivision_id');
	}
}
