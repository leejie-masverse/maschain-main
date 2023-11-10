<?php

namespace Diver\Http\Requests;

use Illuminate\Http\Request;

abstract class QueryRequest
{
    /**
     * Eloquent model class that to be filtered
     *
     * @var string
     */
    protected $model;

    /**
     * Eloquent builder
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Request
     *
     * @var \Illuminate\Http\Request|null
     */
    protected $request;

    /**
     * Filterable
     *
     * @var array
     */
    protected $filterable = [];

    /**
     * Filters collection
     *
     * @var \Illuminate\Support\Collection
     */
    protected $filters = [];

    /**
     * QueryRequest constructor.
     *
     * @param \Illuminate\Http\Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request ? $request : request();

        $this->apply($this->filterable);
    }

    /**
     * Request
     *
     * @return \Illuminate\Http\Request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Filters
     *
     * @return \Illuminate\Support\Collection
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     * Eloquent query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Apply filters
     *
     * @param array $filterable
     *
     * @return $this
     */
    public function apply(array $filterable)
    {
        $filterable = $this->request()->only($filterable);

        $this->filters = $this->normalizeFilters($filterable);

        $this->query = $this->newFilteredQuery($filterable);

        return $this;
    }

    /**
     * Resolve filters
     *
     * @param array $filterable
     *
     * @return \Illuminate\Support\Collection
     */
    protected function normalizeFilters(array $filterable)
    {
        $filterable = hash_flatten($filterable);

        return collect($filterable)->filter()->mapWithKeys(function($value, $filter) {
            $param = compact('value');

            $paramMethod =$this->resolveParamMethod($filter);
            if (method_exists($this, $paramMethod)) {
                $param += $this->$paramMethod($param['value']);
            }

            return [ $filter => $param];
        });
    }

    /**
     * Resolve query
     *
     * @param \Illuminate\Support\Collection $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function newFilteredQuery(array $filterable)
    {
        $query = (new $this->model)->newQuery();

        collect($filterable)->filter()->each(function($value, $filter) use ($query) {
            $filterMethod = $this->resolveFilterMethod($filter);
            if (method_exists($this, $filterMethod)) {
                $this->$filterMethod($query, $value);
            }
        });

        return $query;
    }

    /**
     * Resolve filter normalizer
     *
     * @param $filter
     *
     * @return string
     */
    protected function resolveParamMethod($filter)
    {
        $filter = preg_replace('/\d+/', '', $filter);
        $filter = str_replace('..', '_', $filter);
        $filter = str_replace('.', '', $filter);
        $filter = studly_case($filter);

        return "param{$filter}";
    }

    /**
     * Resolve filter method
     *
     * @param string $filter
     *
     * @return string
     */
    protected function resolveFilterMethod($filter)
    {
        $filter = preg_replace('/\d+/', '', $filter);
        $filter = str_replace('..', '_', $filter);
        $filter = str_replace('.', '', $filter);
        $filter = studly_case($filter);

        return "filter{$filter}";
    }
}
