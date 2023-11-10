<?php

namespace Diver\Database\Eloquent\Traits;

use Diver\Database\Eloquent\Observers\SequenceObserver;
use Illuminate\Database\Eloquent\Builder;

trait Sequenceable
{
    /**
     * Boot Sequence Observer for event handling.
     *
     * @return void
     */
    public static function bootSequenceable()
    {
        static::observe(SequenceObserver::class);
    }

    /**
     * Move model to given position
     *
     * @param $position
     *
     * @return static
     */
    public function sequenceMove($position)
    {
        //@todo handle duplicate position case
        $this->update([ $this->getSequenceColumn() => $position ]);

        return $this;
    }

    /**
     * Move model to the first position
     *
     * @return static
     */
    public function sequenceMoveToFirst()
    {
        $query = $this->newQuery();
        foreach ($this->getSequenceWheres() as $where) {
            $query->where($where, $this->{$where});
        }

        return $this->sequenceMove($query->min($this->getSequenceColumn()) / 2);
    }

    /**
     * Move model to the last position
     *
     * @return static
     */
    public function sequenceMoveToLast()
    {
        $query = $this->newQuery();
        foreach ($this->getSequenceWheres() as $where) {
            $query->where($where, $this->{$where});
        }

        return $this->sequenceMove($query->max($this->getSequenceColumn()) + $this->getSequenceIncrement());
    }

    /**
     * Sequenced scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSequenced(Builder $query, $direction = 'asc')
    {
        foreach ($this->getSequenceWheres() as $where) {
            $query->orderBy($where);
        }

        return $query->orderBy($this->getSequenceColumn(), $direction);
    }

    /**
     * Get sequence increment value
     *
     * @return int
     */
    protected function getSequenceIncrement()
    {
        if (property_exists($this, 'sequenceIncrement')) {
            return $this->sequenceIncrement;
        }

        return 16384; // 2 ^ 16 / 4
    }

    /**
     * Get sequence column
     *
     * @return string
     */
    protected function getSequenceColumn()
    {
        if (property_exists($this, 'sequenceColumn')) {
            return $this->sequenceColumn;
        }

        return 'weight';
    }

    /**
     * Get sequence wheres
     *
     * @return array
     */
    protected function getSequenceWheres()
    {
        if (property_exists($this, 'sequenceWheres')) {
            return $this->sequenceWheres;
        }

        return [];
    }
}
