<?php

namespace Diver\Database\Eloquent\Traits;

use Diver\Database\Eloquent\Builder;
use Diver\Database\Query\Builder as QueryBuilder;

/**
 * Model trait
 *
 * @package Diver\Core\Traits
 */
trait Model
{
    use Linkable;

    /**
     * boot trait
     */
    public static function bootModel()
    {
        static::unguard();
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();

        return new QueryBuilder(
            $connection, $connection->getQueryGrammar(), $connection->getPostProcessor()
        );
    }
}
