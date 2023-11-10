@php
$hasFilters = $filters->isNotEmpty();
@endphp
<section class="table-filter">
    <div class="row">
        <div class="col">
            @if($hasFilters)
                <div class="m-list-badge m-list-badge--light-bg my-2">
                    <span class="m-list-badge__label">Filters:</span>
                    <ul class="list-inline m-list-badge__items">
                        @foreach($filters as $filter => $param)
                            @php
                                $routeFilterRouteParameters = $filters->except($filter)->map(function($param) {
                                    return $param['value'];
                                });
                                {{isset($id)?$removeFilterRoute=route(Route::currentRouteName(), [ 'id' => $id],hash_expand($routeFilterRouteParameters->all())):
                                $removeFilterRoute = route(Route::currentRouteName(), hash_expand($routeFilterRouteParameters->all()));
                            }}
                            @endphp
                            <li
                                class="list-inline-item m-list-badge__item"
                            >
                                {{ $param['title'] }}: {{ $param['formattedValue'] }}
                                <a
                                    class="btn btn-outline-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill m-list-badge__item-remove ml-2"
                                    href="{{ $removeFilterRoute }}"
                                >
                                    <i class="la la-times"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="col-sm-3 text-right">
            <button
                id="{{ $target }}-toggler"
                class="btn btn-outline-info"
                data-quick-sidebar="{{ $target }}"
                type="button"
            >
                <i class="la la-filter"></i> Filter
            </button>
            @if($hasFilters)
                <a
                    class="btn btn-secondary"
                    href="{{ isset($id)?route(Route::currentRouteName(), [ 'id' => $id]):route(Route::currentRouteName()) }}"
                >
                    <i class="la la-times"></i> Clear
                </a>
            @endif
        </div>
    </div>
</section>
