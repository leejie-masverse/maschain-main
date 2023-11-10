<?php

namespace Diver\Field;

use Diver\Dataset\City;
use Diver\Dataset\Country;
use Diver\Dataset\CountrySubdivision;
use Diver\Field\Observers\AddressObserver;

abstract class Address extends Field
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'fields_address';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'address',
		'street1',
		'street2',
		'postal_code',
		'city',
		'city_id',
		'subdivision',
		'subdivision_id',
		'country',
		'country_id',
		'latitude',
		'longitude',
	];

	/**
	 * Field name
	 *
	 * @var string
	 */
	protected $field = 'address';

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::observe(AddressObserver::class);
	}

	/**
	 * Belongs to city
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function city()
	{
		return $this->belongsTo(City::class, 'city_id');
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
	 * Get address's city
	 *
	 * @param string $value
	 * @return \Diver\Dataset\City|string
	 */
	public function getCityAttribute($value)
	{
		if ($this->city_id) {
			return $this->getRelationValue('city');
		}

		return $value;
	}

	/**
	 * Get address's subdivision
	 *
	 * @param string $value
	 * @return \Diver\Dataset\CountrySubdivision|string
	 */
	public function getSubdivisionAttribute($value)
	{
		if (!empty($this->subdivision_id)) {
            return $this->getRelationValue('subdivision');
		}

		return $value;
	}

	/**
	 * Get address's country
	 *
	 * @param string $value
	 * @return \Diver\Dataset\Country|string
	 */
	public function getCountryAttribute($value)
	{
		if (!empty($this->country_id)) {
			return $this->getRelationValue('country');
		}

		return $value;
	}

	/**
	 * Get address in line
	 *
	 * @return string
	 */
	public function getAddressLineAttribute()
	{
		if (!$this->address) {
			return;
		}

		return implode(', ', preg_split('/[\n\r]+/', $this->address));
	}

    /**
     * Include name, and contact number to address
     *
     * @group
     * @return string
     */
    public function getFullAddressDetails()
    {
        return "{$this->name}\n{$this->phone_number}\n{$this->address}";
	}
}
