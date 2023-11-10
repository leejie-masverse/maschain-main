<?php

namespace Diver\Field;

use Diver\Database\Eloquent\Model;
use Diver\Field\Exception\EmptyFieldPropertyException;
use Diver\Field\Observers\FieldObserver;
use Illuminate\Database\Eloquent\Builder;

abstract class Field extends Model
{
	/**
	 * Field name
	 *
	 * @var string
	 */
	protected $field;

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('fieldable', function (Builder $query) {
			$field = (new static)->getField();

			$query->where('field', $field);
		});

		static::observe(FieldObserver::class);
	}

    /**
     * Get the field name for model
     *
     * @return string
     * @throws EmptyFieldPropertyException
     */
	public function getField()
	{
	    if (empty($this->field)) {
	        throw new EmptyFieldPropertyException(__CLASS__ . '\'s field property is unset.');
        }

		return $this->field;
	}

	/**
	 * Belongs to entity
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function entity()
	{
		return $this->morphTo('entity');
	}
}
