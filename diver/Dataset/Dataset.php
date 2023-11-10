<?php

namespace Diver\Dataset;

use Diver\Database\Eloquent\Model;

abstract class Dataset extends Model
{
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;
}
