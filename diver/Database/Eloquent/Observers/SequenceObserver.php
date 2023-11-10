<?php

namespace Diver\Database\Eloquent\Observers;

use Illuminate\Database\Eloquent\Model;

class SequenceObserver
{
	/**
	 * Creating
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 */
	public function created(Model $model)
	{
		$model->sequenceMoveToLast();
	}
}