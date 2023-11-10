<?php

namespace Diver\Field;

use Diver\Dataset\Country;
use Diver\Field\Observers\PhoneObserver;

abstract class Phone extends Field
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'fields_phone';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'phone',
		'country_code',
		'number',
		'country_id',
	];

	/**
	 * Field name
	 *
	 * @var string
	 */
	protected $field = 'phone';

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::observe(PhoneObserver::class);
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
	 * Return phone number with + prefix
	 *
	 * @return string
	 */
	public function getPhoneWithPlus()
	{
		return "+{$this->phone}";
	}
}