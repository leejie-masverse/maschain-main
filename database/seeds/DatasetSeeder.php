<?php

use Diver\Dataset\Country;
use Diver\Dataset\CountrySubdivision;
use Diver\Dataset\City;
use Diver\Dataset\Facades\CountrySubdivisionRepository;
use Diver\Dataset\Facades\CountryRepository;
use Illuminate\Database\Seeder;

class DatasetSeeder extends Seeder
{
	protected $countries = [
		[
			'country.name' => 'Malaysia',
			'country.iso_code_alpha2' => 'MY',
			'country.iso_code_alpha3' => 'MYS',
			'country.calling_code' => '60',
			'country.address_format' => [
				':street1',
				':street2',
				':postal_code :city',
				':subdivision',
				':country',
			],
		],
        [
            'country.name' => 'Singapore',
            'country.iso_code_alpha2' => 'SG',
            'country.iso_code_alpha3' => 'SGP',
            'country.calling_code' => '65',
            'country.address_format' => [
                ':street1',
                ':street2',
                ':city :postal_code',
                ':country',
            ],
        ],
	];

	protected $subdivisions = [
		'MY' => [
			[
				'country_subdivision.name' => 'Johor',
				'country_subdivision.iso_code' => 'MY-01',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Kedah',
				'country_subdivision.iso_code' => 'MY-02',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Kelantan',
				'country_subdivision.iso_code' => 'MY-03',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Melaka',
				'country_subdivision.iso_code' => 'MY-04',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Negeri Sembilan',
				'country_subdivision.iso_code' => 'MY-05',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Pahang',
				'country_subdivision.iso_code' => 'MY-06',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Penang',
				'country_subdivision.iso_code' => 'MY-07',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Perak',
				'country_subdivision.iso_code' => 'MY-08',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Perlis',
				'country_subdivision.iso_code' => 'MY-09',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Selangor',
				'country_subdivision.iso_code' => 'MY-10',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Terengganu',
				'country_subdivision.iso_code' => 'MY-11',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Sabah',
				'country_subdivision.iso_code' => 'MY-12',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Sarawak',
				'country_subdivision.iso_code' => 'MY-13',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_STATE,
			],
			[
				'country_subdivision.name' => 'Kuala Lumpur',
				'country_subdivision.iso_code' => 'MY-14',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_FEDERAL_TERRITORY,
			],
			[
				'country_subdivision.name' => 'Labuan',
				'country_subdivision.iso_code' => 'MY-15',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_FEDERAL_TERRITORY,
			],
			[
				'country_subdivision.name' => 'Putrajaya',
				'country_subdivision.iso_code' => 'MY-16',
				'country_subdivision.category' => CountrySubdivision::CATEGORY_FEDERAL_TERRITORY,
			],
		],
        'SG' => [
            [
                'country_subdivision.name' => 'Central Singapore',
                'country_subdivision.iso_code' => 'SG-01',
                'country_subdivision.category' => CountrySubdivision::CATEGORY_DISTINCT,
            ],
            [
                'country_subdivision.name' => 'North East',
                'country_subdivision.iso_code' => 'SG-02',
                'country_subdivision.category' => CountrySubdivision::CATEGORY_DISTINCT,
            ],
            [
                'country_subdivision.name' => 'North West',
                'country_subdivision.iso_code' => 'SG-03',
                'country_subdivision.category' => CountrySubdivision::CATEGORY_DISTINCT,
            ],
            [
                'country_subdivision.name' => 'South East',
                'country_subdivision.iso_code' => 'SG-04',
                'country_subdivision.category' => CountrySubdivision::CATEGORY_DISTINCT,
            ],
            [
                'country_subdivision.name' => 'South West',
                'country_subdivision.iso_code' => 'SG-05',
                'country_subdivision.category' => CountrySubdivision::CATEGORY_DISTINCT,
            ],
        ],
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $this->seedCountries();
	    $this->seedSubdivisions();
    }

    /**
     * Seed Countries
     */
    protected function seedCountries()
    {
	    collect($this->countries)->each(function ($data) {
            $input = hash_expand($data);
            CountryRepository::create($input);
	    });
    }

    /**
     * Seed Subdivisions
     */
    protected function seedSubdivisions()
    {
        collect($this->subdivisions)->each(function ($subdivisions, $countryCode) {
            $this->seedSubdivision($subdivisions, $countryCode);
        });
    }

    /**
     * Seed a Subdivision
     *
     * @param $subdivisions
     * @param $countryCode
     */
    protected function seedSubdivision($subdivisions, $countryCode){

        $country = Country::where('iso_code_alpha2', $countryCode)->first();

        collect($subdivisions)->each(function ($data) use ($country) {
            $input = hash_expand($data);
            $input['country_subdivision']['country_id'] = $country->id;
            CountrySubdivisionRepository::create($input);
        });

    }
}
