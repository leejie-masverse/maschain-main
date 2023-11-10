<?php

namespace Diver\Field\Observers;

use Diver\Field\Person;

class PersonObserver
{
	/**
	 * Saving
	 *
	 * @param \Diver\Field\Person $person
	 */
	public function saving(Person $person)
	{
		$this->populateFullName($person);
	}

	/**
	 * Popuplate full name
	 *
	 * @param \Diver\Field\Person $person
	 */
	protected function populateFullName(Person $person)
	{
		if ($person->isClean(['given_name', 'family_name'])) {
			return;
		}

		$fullName = "{$person->given_name} {$person->family_name}";
		$person->full_name = trim($fullName) ?: null;
	}
}