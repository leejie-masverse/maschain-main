<?php

namespace Diver\Field\Observers;

use Diver\Field\Field;

class FieldObserver
{
	/**
	 * Saving
	 *
	 * @param \Diver\Field\Field $field
	 */
	public function saving(Field $field)
	{
		$field->field = $field->getField();
	}
}