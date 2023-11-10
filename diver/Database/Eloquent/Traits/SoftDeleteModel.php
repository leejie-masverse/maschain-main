<?php

namespace Diver\Database\Eloquent\Traits;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

trait SoftDeleteModel
{
    use Model;
    use SoftCascadeTrait;
    use SoftDeletes;
}
