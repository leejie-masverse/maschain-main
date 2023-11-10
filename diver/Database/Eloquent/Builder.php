<?php

namespace Diver\Database\Eloquent;

use Diver\Database\Eloquent\Traits\LinkRelationship;
use Illuminate\Database\Eloquent\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    use LinkRelationship;
}