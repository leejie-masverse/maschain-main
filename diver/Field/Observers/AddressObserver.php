<?php

namespace Diver\Field\Observers;

use Diver\Dataset\Country;
use Diver\Dataset\CountrySubdivision;
use Diver\Field\Address;

class AddressObserver
{
	/**
	 * Saving
	 *
	 * @param \Diver\Field\Address $address
	 */
	public function saving(Address $address)
	{
		$this->populateCity($address);
		$this->populateSubdivision($address);
		$this->populateCountry($address);
		$this->populateAddress($address);
	}

	/**
	 * Populate city
	 *
	 * @param \Diver\Field\Address $address
	 */
	protected function populateCity(Address $address)
	{
		if ($address->isClean('city_id')) {
			return;
		}

		$address->city = $address->city->name;
	}

	/**
	 * Populate subdivision
	 *
	 * @param \Diver\Field\Address $address
	 */
	protected function populateSubdivision(Address $address)
	{
		if ($address->isClean('subdivision_id')) {
			return;
		}

		$address->subdivision = CountrySubdivision::findOrFail($address->subdivision_id)->name;
	}

	/**
	 * Populate country
	 *
	 * @param \Diver\Field\Address $address
	 */
	protected function populateCountry(Address $address)
	{
		if ($address->isClean('country_id')) {
			return;
		}

		$address->country = Country::findOrFail($address->country_id)->name;
	}

	/**
	 * Populate address
	 *
	 * @param \Diver\Field\Address $address
	 */
	protected function populateAddress(Address $address)
	{
		$columns = ['unit_no', 'building_name', 'street1', 'street2', 'postal_code', 'city', 'subdivision', 'country'];
		if ($address->isClean($columns)) {
			return;
		}

		$addressFormat = Country::findOrFail($address->country_id)->address_format;
		$replaces = data_all($address->getAttributes(), $columns);

		$lines = collect($addressFormat)->map(function ($line) use ($address, $replaces) {
			return text_insert($line, $replaces);
		});

		$address->address = $lines->filter(function($line) {
		    return !empty(trim($line));
        })->implode("\n");
	}
}
