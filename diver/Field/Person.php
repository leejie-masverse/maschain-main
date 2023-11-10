<?php

namespace Diver\Field;

use Diver\Dataset\Country;
use Diver\Field\Observers\PersonObserver;

abstract class Person extends Field
{
	/**
	 * Gender
	 */
	const GENDER_MALE = 'male';
	const GENDER_FEMALE	= 'female';

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
    protected $table = 'fields_person';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'full_name',
		'given_name',
		'family_name',
		'gender',
		'date_of_birth',
		'nationality',
		'nationality_id',
		'national_identity',
	];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'date_of_birth',
	];

	/**
	 * Field name
	 *
	 * @var string
	 */
	protected $field = 'person';

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::observe(PersonObserver::class);
	}

	/**
	 * Belongs to nationality
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function nationality()
	{
		return $this->belongsTo(Country::class, 'nationality_id');
	}

	/**
	 * Get person nationality
	 *
	 * @param string $value
	 * @return \App\Datasets\Country|string
	 */
	public function getNationalityAttribute($value)
	{
		if ($this->nationality_id) {
			return $this->getRelationValue('nationality');
		}

		return $value;
	}

	public function getInitial()
    {

    }
}

