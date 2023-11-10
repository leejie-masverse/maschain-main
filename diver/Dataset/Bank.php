<?php

namespace Diver\Dataset;

class Bank extends Dataset
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'dataset_banks';

	CONST IMAGE_DIRECTORY = 'banks/';

	CONST TYPE_BANK = "bank";
	CONST TYPE_PAYMENT = "payment";
}
