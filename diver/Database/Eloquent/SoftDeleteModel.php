<?php

namespace Diver\Database\Eloquent;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class SoftDeleteModel extends Model
{
    use SoftCascadeTrait;
    use SoftDeletes;
}
