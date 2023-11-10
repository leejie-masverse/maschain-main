@component('manage.components.quick-sidebar', [
    'id' => $id
])
    @php
        $para = isset($para)? $para : 'id'
    @endphp
    @component('manage.components.portlet', [
        'unair' => true,
        'headText' => 'Filter Records',
        'headIcon' => '',
        'formAction' => isset($user_id)&&isset($para)?route(Route::currentRouteName(), [ $para => $user_id]):route(Route::currentRouteName()),
        'formMethod' => 'get',
        'formXlColOffset' => 0
    ])
        {{ $slot }}

        @slot('formActionsLeft')
            <button
                type="submit"
                class="btn btn-info"
            >
                <i class="la la-filter"></i>
                Filter
            </button>
        @endslot

    @endcomponent
@endcomponent
