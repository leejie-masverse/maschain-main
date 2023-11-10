<?php

namespace Diver\Database\Eloquent;

use Diver\Database\Eloquent\Traits\Model as ModelContract;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use ModelContract;
}
