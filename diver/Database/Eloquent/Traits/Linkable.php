<?php

namespace Diver\Database\Eloquent\Traits;

use Diver\Database\Eloquent\Builder;

/**
 * Linkable Trait
 *
 * @package Diver\Core\Traits
 *
 * @todo finish linkMorphTo and linkHasOne
 * @todo preset relation where clause
 * @todo support soft delte
 */
trait Linkable
{
    /**
     * Where linked scope
     *
     * @param \Diver\Database\Eloquent\Builder $query
     * @param string|array|\Closure $column
     * @param string $operator
     * @param mixed $value
     * @param string $boolean
     */
    public function scopeWhereLinked(Builder $query, $column, $operator = null, $value = null, $boolean = 'and')
    {
        $query->where($query->linkedColumn($column), $operator, $value, $boolean);
    }

    /**
     * Or where linked scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array|\Closure $column
     * @param string $operator
     * @param mixed $value
     */
    public function scopeOrWhereLinked(Builder $query, $column, $operator = null, $value = null)
    {
        $query->orWhere($query->linkedColumn($column), $operator, $value);
    }

    /**
     * Order by linked scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $column
     * @param string $sortBy
     */
    public function scopeOrderByLinked(Builder $query, $column, $sortBy = 'asc')
    {
//        dd(get_class($query));
        $query->orderBy($query->linkedColumn($column), $sortBy);
    }
}
