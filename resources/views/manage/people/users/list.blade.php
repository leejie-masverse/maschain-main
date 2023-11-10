@extends('manage.layouts.admin', [ 'pageTitle' => 'Users Overview' ])

@section('content')
    @component('manage.components.portlet', [
        'headText' => 'Users',
        'headIcon' => 'flaticon-user',
        'unair' => true,
        'formAction' => route(Route::currentRouteName()),
        'formMethod' => 'get',
        'formXlColOffset' => 0
    ])

{{--        @slot('headTools')--}}
{{--            <ul class="m-portlet__nav">--}}
{{--                <li class="m-portlet__nav-item">--}}
{{--                    <a--}}
{{--                        class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill"--}}
{{--                        href="{{ route('manage.store.order.orders.create') }}"--}}
{{--                    >--}}
{{--                      <span>--}}
{{--                            <i class="la la-plus"></i>--}}
{{--                            <span>New Order</span>--}}
{{--                      </span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        @endslot--}}

        <div class="d-flex">
            <div class="form-group m-form__group w-100">
                <label>Search</label>
                <input
                    type="text"
                    class="form-control m-input"
                    name="search"
                    value="{{ request('search') }}"
                >
            </div>

{{--            <button--}}
{{--                type="submit"--}}
{{--                class="btn btn-info h-100 ml-3"--}}
{{--                style="margin-top:2rem"--}}
{{--            >--}}
{{--                <i class="la la-filter"></i>--}}
{{--                Search--}}
{{--            </button>--}}
        </div>
        @slot('formActionsLeft')
        @endslot
        @include('manage.components.table-filter', [ 'target' => 'users-filter-quick-sidebar' ])
        <form>
            @csrf
        </form>
        <div class="table-responsive my-4">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th colspan="2">User</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @php
                        $statusBadgeMap = [
                            Src\People\User::STATUS_ACTIVE => 'success',
                            Src\People\User::STATUS_BANNED => 'danger',
                            Src\People\User::STATUS_VERIFYING => 'info',
                        ];
                    @endphp
                    @foreach($users as $user)
                        @php
                            $banUserFormId = "ban-user-{$user->id}-form";
                            $unbanUserFormId = "unban-user-{$user->id}-form";
                            $releaseUserFormId = "release-user-{$user->id}-form";
                            $deleteUserFormId = "delete-user-{$user->id}-form";
                            $verifyUserFormId = "verify-user-{$user->id}-form";
                        @endphp
                        <tr>
                            <td width="48">
                                <img
                                    class="m--img-rounded"
                                    src="{{ $user->portrait->url }}"
                                    width="48"
                                    height="48"
                                >
                            </td>
                            <td>
                                <a href="{{ route('manage.people.users.list', [ 'referred_by' => $user->id ]) }}">{{ $user->profile->full_name }}</a>

                                @if ($user->isAuthed())
                                    @component('manage.components.badge', [ 'type' => 'info', 'rounded' => true ])
                                        You
                                    @endcomponent
                                @endif
                                <br>
                                <span>Affiliate ID: {{ $user->affiliate_id }}</span>
                            </td>
                            <td>
                                <a class="m-link" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                            </td>
                            <td>
                                {{ $user->formatted_phone_number }}
                            </td>
                            <td>
                                @component('manage.components.badge', [ 'type' => $statusBadgeMap[$user->status] ])
                                    {{ $user->status }}
                                @endcomponent
                            </td>
                            <td>

{{--                                <a--}}
{{--                                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                    href="{{ route('manage.people.users.show', [ 'id' => $user->id ]) }}"--}}
{{--                                    title="Details"--}}
{{--                                >--}}
{{--                                    <i class="la la-eye"></i>--}}
{{--                                </a>--}}
                                @if ($user->isAuthed())
                                    <a
                                        class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                        href="{{ route('manage.account.profile.edit') }}"
                                        title="Edit"
                                    >
                                        <i class="la la-edit"></i>
                                    </a>
                                    <a
                                        class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                        href="{{ route('manage.account.profile.edit-password') }}"
                                        title="Change Password"
                                    >
                                        <i class="la la-key"></i>
                                    </a>
                                @else
                                @if($user->isVerifying())
                                    <button
                                        class="btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Verify"
                                        form="{{ $verifyUserFormId }}"
                                    >
                                        <i class="la la-check"></i>
                                    </button>
                                    <form
                                        id="{{ $verifyUserFormId }}"
                                        class="m-form d-none"
                                        method="post"
                                        action="{{ route('manage.people.users.verify', [ 'id' => $user->id ]) }}"
                                        data-confirm="true"
                                        data-confirm-type="warning"
                                        data-confirm-title="Verify <strong>{{ $user->profile->full_name }}</strong>"
                                        data-confirm-text="You are about to verify this user."
                                    >
                                        @method('post')
                                        @csrf
                                    </form>
                                @endIf
                                <a
                                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    href="{{ route('manage.people.users.edit', [ 'id' => $user->id ]) }}"
                                    title="Edit"
                                >
                                    <i class="la la-edit"></i>
                                </a>
                                <a
                                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    href="{{ route('manage.people.users.edit-password', [ 'id' => $user->id ]) }}"
                                    title="Change Password"
                                >
                                    <i class="la la-key"></i>
                                </a>
                                @if ($user->isActive())
                                    <button
                                        class="btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Ban"
                                        form="{{ $banUserFormId }}"
                                    >
                                        <i class="la la-lock"></i>
                                    </button>
                                    <form
                                        id="{{ $banUserFormId }}"
                                        class="m-form d-none"
                                        method="post"
                                        action="{{ route('manage.people.users.ban', [ 'id' => $user->id ]) }}"
                                        data-confirm="true"
                                        data-confirm-type="warning"
                                        data-confirm-title="Ban <strong>{{ $user->profile->full_name }}</strong>"
                                        data-confirm-text="You are about to ban this user."
                                    >
                                        @method('post')
                                        @csrf
                                    </form>
                                @endif
                                @if ($user->isBanned())
                                    <button
                                        class="btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Unban"
                                        form="{{ $unbanUserFormId }}"
                                    >
                                        <i class="la la-unlock-alt"></i>
                                    </button>
                                    <form
                                        id="{{ $unbanUserFormId }}"
                                        class="m-form d-none"
                                        method="post"
                                        action="{{ route('manage.people.users.unban', [ 'id' => $user->id ]) }}"
                                        data-confirm="true"
                                        data-confirm-type="warning"
                                        data-confirm-title="Unban <strong>{{ $user->profile->full_name }}</strong>"
                                        data-confirm-text="You are about to unban this user."
                                    >
                                        @method('delete')
                                        @csrf
                                    </form>
                                @endif
                                <button
                                    class="btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Release"
                                    form="{{ $releaseUserFormId }}"
                                >
                                    <i class="la la-sign-out"></i>
                                </button>
                                <form
                                    id="{{ $releaseUserFormId }}"
                                    class="m-form d-none"
                                    method="post"
                                    action="{{ route('manage.people.users.release', [ 'id' => $user->id ]) }}"
                                    data-confirm="true"
                                    data-confirm-type="warning"
                                    data-confirm-title="Release <strong>{{ $user->profile->full_name }}</strong>"
                                    data-confirm-text="You are about to release this user. This action is irreversible"
                                >
                                    @method('post')
                                    @csrf
                                </form>
                                <button
                                    class="btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Delete"
                                    form="{{ $deleteUserFormId }}"
                                >
                                    <i class="la la-trash"></i>
                                </button>
                                <form
                                    id="{{ $deleteUserFormId }}"
                                    class="m-form d-none"
                                    method="post"
                                    action="{{ route('manage.people.users.destroy', [ 'id' => $user->id ]) }}"
                                    data-confirm="true"
                                    data-confirm-type="delete"
                                    data-confirm-title="Delete <strong>{{ $user->profile->full_name }}</strong>"
                                    data-confirm-text="You are about to delete this user, this procedure is irreversible."
                                >
                                    @method('delete')
                                    @csrf
                                </form>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            @include('manage.components.pagination', [ 'results' => $users ])
                            @include('manage.components.pagination-counter', ['results' => $users])
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

    @endcomponent
@endsection

@push('quick-sidebar')
    @component('manage.components.table-filter-quick-sidebar', [
        'id' => 'users-filter-quick-sidebar'
    ])
        <fieldset class="m-form__section m-form__section--first">
            <div class="form-group m-form__group">
                <label>Search</label>
                <input
                    type="text"
                    class="form-control m-input"
                    name="search"
                    value="{{ request('search') }}"
                >
            </div>
            <div class="form-group m-form__group">
                <label>Status</label>
                <div class="m-checkbox-list">
                    <label class="m-checkbox">
                        <input
                            type="checkbox"
                            name="status[]"
                            value="{{ \Src\People\User::STATUS_VERIFYING }}"
                            {{ in_array(\Src\People\User::STATUS_VERIFYING, request('status', [])) ? 'checked' : '' }}
                        >
                        Verifying
                        <span></span>
                    </label>
                    <label class="m-checkbox">
                        <input
                            type="checkbox"
                            name="status[]"
                            value="{{ \Src\People\User::STATUS_ACTIVE }}"
                            {{ in_array(\Src\People\User::STATUS_ACTIVE, request('status', [])) ? 'checked' : '' }}
                        >
                        Active
                        <span></span>
                    </label>
                    <label class="m-checkbox">
                        <input
                            type="checkbox"
                            name="status[]"
                            value="{{ \Src\People\User::STATUS_BANNED }}"
                            {{ in_array(\Src\People\User::STATUS_BANNED, request('status', [])) ? 'checked' : '' }}
                        >
                        Banned
                        <span></span>
                    </label>
                </div>
                <span class="m-form__help">Status of user</span>
            </div>

            {{--<div class="form-group m-form__group">--}}
            {{--<label>From</label>--}}
            <input
                id="dateFrom"
                type="date"
                name="dateFrom"
                class="form-control m-input"
                hidden
                value="{{ request('dateFrom') }}"
            >
            {{--</div>--}}
            {{--<div class="form-group m-form__group">--}}
            {{--<label>Before</label>--}}
            <input
                id="dateBefore"
                type="date"
                name="dateBefore"
                class="form-control m-input"
                hidden
                value="{{ request('dateBefore') }}"
            >

            <div class="form-group m-form__group">
                <label>Date</label>

                <div class="m-checkbox-list mt-3">
                    <label class="m-checkbox">
                        <input
                            type="radio"
                            name="specificDate"
                            value="today"
                        >
                        Today
                        <span></span>
                    </label>
                    <label class="m-checkbox">
                        <input
                            type="radio"
                            name="specificDate"
                            value="one_week"
                        >
                        Within a week
                        <span></span>
                    </label>
                    <label class="m-checkbox">
                        <input
                            type="radio"
                            name="specificDate"
                            value="this_month"
                        >
                        This month
                        <span></span>
                    </label>
                    <label class="m-checkbox">
                        <input
                            type="radio"
                            name="specificDate"
                            value=""
                            data-target="date_range"
                            class="{{request('dateFrom')!==null && request('dateBefore')!==null?'radioChecked':''}}"
                            {{request('dateFrom')!==null && request('dateBefore')!==null?'checked':''}}
                        >
                        Date Range
                        <span></span>
                    </label>
                    <input
                        id="dateRange"
                        type="text"
                        name="dateRange"
                        class="form-control m-input"
                        @if ((preg_match("/^[0-9]{4}\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2][0-9]|3[0-1])$/",request('dateBefore')))
                        &&(preg_match("/^[0-9]{4}\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2][0-9]|3[0-1])$/",request('dateFrom'))))
                        value="{{ date_format(DateTime::createFromFormat('Y-m-d', request('dateFrom')),'m/d/Y')
                        .' - '.date_format(DateTime::createFromFormat('Y-m-d', request('dateBefore')),'m/d/Y')}}"
                        @elseif((preg_match("/^[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/",request('dateBefore')))
                        &&(preg_match("/^[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/",request('dateFrom'))))
                        value="{{ request('dateFrom').' - '.request('dateBefore') }}"
                        @else
                        value=""
                        @endif
                    >
                </div>
            </div>

            {{--</div>--}}
{{--            <div class="form-group m-form__group">--}}
{{--                <label>Date</label>--}}
{{--                <input--}}
{{--                    id="dateRange"--}}
{{--                    type="text"--}}
{{--                    name="dateRange"--}}
{{--                    class="form-control m-input"--}}
{{--                    --}}{{--value="01/01/2018 - 01/15/2018"--}}
{{--                    --}}{{--value="{{ request('date') }}"--}}
{{--                >--}}
{{--            </div>--}}
        </fieldset>
    @endcomponent
@endpush
