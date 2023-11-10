<?php

namespace Diver\Dataset;

class CountrySubdivision extends Dataset
{
	/**
	 * Category enum
	 */
    CONST CATEGORY_DISTINCT = 'distinct';
    CONST CATEGORY_FEDERAL_TERRITORY = 'federal territory';
    CONST CATEGORY_PROVINCE = 'province';
    CONST CATEGORY_REGION = 'region';
    CONST CATEGORY_STATE = 'state';
    CONST CATEGORY_TERRITORY = 'territory';

    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'dataset_country_subdivisions';

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
	 * Belongs to cities
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function cities()
	{
		return $this->hasMany(City::class, 'country_subdivision_id');
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

	/**
	 * Of country scope
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param mixed $lookup
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeOfCountry($query, $lookup)
	{
		$country = Country::where('id', $lookup)->orWhere('name', $lookup)->firstOrFail();
		return $query->where('country_id', $country->id);
	}

	/**
	 * Of default country scope
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param mixed $lookup
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeOfDefaultCountry($query)
	{
		return $this->scopeOfCountry($query, config('datasets.address.default_country'));
	}
}
