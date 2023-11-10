<?php

namespace Diver\Field\Observers;

use Diver\Dataset\Country;
use Diver\Field\Phone;

class PhoneObserver
{
	/**
	 * Saving
	 *
	 * @param \Diver\Field\Phone $phone
	 */
	public function saving(Phone $phone)
	{
		$this->populateCountryCode($phone);
		$this->populatePhone($phone);
	}

	/**
	 * Populate country code
	 *
	 * @param \Diver\Field\Phone $phone
	 */
	protected function populateCountryCode(Phone $phone)
	{
		if ($phone->isClean('country_id')) {
			return;
		}

		$phone->country_code = Country::find($phone->country_id)->calling_code;
	}

	/**
	 * Populate phone number
	 *
	 * @param \Diver\Field\Phone $phone
	 */
	protected function populatePhone(Phone $phone)
	{
		if ($phone->isClean(['country_code', 'number'])) {
			return;
		}

		$number = "{$phone->country_code}{$phone->number}";
		$phone->phone = trim($number) ?: null;
	}
}