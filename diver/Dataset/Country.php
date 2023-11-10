<?php

namespace Diver\Dataset;

class Country extends Dataset
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'dataset_countries';

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'address_format' => 'array',
	];

	/**
	 * Has many country subdivisions
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subdivisions()
	{
		return $this->hasMany(CountrySubdivision::class, 'country_id');
	}

	/**
	 * Has many cities
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function cities()
	{
		return $this->hasMany(City::class, 'country_id');
	}

	/**
	 * Belongs to capital city
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function capitalCity()
	{
		return $this->belongsTo(City::class, 'capital_city_id');
	}

	/**
	 * get the country's capital city name
	 *
	 * @param string $value
	 * @return string
	 */
	public function getCapitalCityAttribute($value)
	{
		if (!empty($this->capital_city_id)) {
			return $this->getRelation('capitalCity');
		}

		return $value;
	}
}
