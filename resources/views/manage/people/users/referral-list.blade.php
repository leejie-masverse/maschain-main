{{--@extends('manage.layouts.admin', [ 'pageTitle' => 'Referral Partners Overview' ])--}}

{{--@section('content')--}}
{{--    @component('manage.components.portlet', [--}}
{{--        'headText' => 'Referral Partners',--}}
{{--        'headIcon' => 'flaticon-user',--}}
{{--    ])--}}
{{--        @slot('headTools')--}}
{{--            <ul class="m-portlet__nav">--}}
{{--                <li class="m-portlet__nav-item">--}}
{{--                    <a--}}
{{--                        class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill"--}}
{{--                        href="{{ route('manage.people.users.create') }}"--}}
{{--                    >--}}
{{--                      <span>--}}
{{--                            <i class="la la-plus"></i>--}}
{{--                            <span>New Partner</span>--}}
{{--                      </span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        @endslot--}}

{{--        @include('manage.components.table-filter', [ 'target' => 'users-filter-quick-sidebar','id'=> $user_id ])--}}

{{--        <div class="table-responsive my-4">--}}
{{--            <table class="table table-hover">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th colspan="2">User</th>--}}
{{--                    <th>Email</th>--}}
{{--                    <th>Phone</th>--}}
{{--                    <th>Level</th>--}}
{{--                    <th>Status</th>--}}
{{--                    <th>Actions</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @php--}}
{{--                    $roleBadgeMap = [--}}
{{--                      Src\People\User::LEVEL_PARTNER => 'info',--}}
{{--                      Src\People\User::LEVEL_MASTER_PARTNER => 'brand',--}}
{{--                      Src\People\User::USER_LEVEL_1 => 'info',--}}
{{--                      Src\People\User::USER_LEVEL_2 => 'info',--}}
{{--                      Src\People\User::USER_LEVEL_3 => 'info',--}}
{{--                      Src\People\User::USER_LEVEL_4 => 'info',--}}
{{--                    ];--}}

{{--                    $statusBadgeMap = [--}}
{{--                        Src\People\User::STATUS_ACTIVE => 'success',--}}
{{--                        Src\People\User::STATUS_BANNED => 'danger',--}}
{{--                        Src\People\User::STATUS_VERIFYING => 'warninig',--}}
{{--                    ];--}}
{{--                @endphp--}}
{{--                @foreach($users as $user)--}}
{{--                    @php--}}
{{--                        $banUserFormId = "ban-user-{$user->id}-form";--}}
{{--                        $unbanUserFormId = "unban-user-{$user->id}-form";--}}
{{--                        $deleteUserFormId = "delete-user-{$user->id}-form";--}}
{{--                    @endphp--}}
{{--                    <tr>--}}
{{--                        <td width="48">--}}
{{--                            <img--}}
{{--                                class="m--img-rounded"--}}
{{--                                src="{{ $user->portrait->url }}"--}}
{{--                                width="48"--}}
{{--                                height="48"--}}
{{--                            >--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            {{ $user->profile->full_name }}--}}
{{--                            @if ($user->isAuthed())--}}
{{--                                @component('manage.components.badge', [ 'type' => 'info', 'rounded' => true ])--}}
{{--                                    You--}}
{{--                                @endcomponent--}}
{{--                            @endif--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            <a class="m-link" href="mailto:{{ $user->email }}">{{ $user->email }}</a>--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            {{ $user->formatted_phone_number }}--}}
{{--                        </td>--}}
{{--                        @if($user->partner_level!=null)--}}
{{--                        <td class="m--font-{{ $roleBadgeMap[$user->partner_level] }}">--}}
{{--                            @include('manage.components.badge--dot', [ 'type' => $roleBadgeMap[$user->partner_level] ])--}}
{{--                            {{ $user->getRank() }}--}}
{{--                        </td>--}}
{{--                        @else--}}
{{--                            <td class="m--font-{{ $roleBadgeMap[$user->user_level] }}">--}}
{{--                                @include('manage.components.badge--dot', [ 'type' => $roleBadgeMap[$user->user_level] ])--}}
{{--                                {{ $user->getRank() }}--}}
{{--                            </td>--}}
{{--                        @endIf--}}
{{--                        <td>--}}
{{--                            @component('manage.components.badge', [ 'type' => $statusBadgeMap[$user->status] ])--}}
{{--                                {{ $user->status }}--}}
{{--                            @endcomponent--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            @if ($user->isAuthed())--}}
{{--                                <a--}}
{{--                                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                    href="{{ route('manage.people.users.referral', [ 'id' => $user->id ]) }}"--}}
{{--                                    title="View"--}}
{{--                                >--}}
{{--                                    <i class="la la-eye"></i>--}}
{{--                                </a>--}}
{{--                                <a--}}
{{--                                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                    href="{{ route('manage.account.profile.edit') }}"--}}
{{--                                    title="Edit"--}}
{{--                                >--}}
{{--                                    <i class="la la-edit"></i>--}}
{{--                                </a>--}}
{{--                                <a--}}
{{--                                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                    href="{{ route('manage.account.profile.edit-password') }}"--}}
{{--                                    title="Change Password"--}}
{{--                                >--}}
{{--                                    <i class="la la-key"></i>--}}
{{--                                </a>--}}
{{--                            @else--}}
{{--                                <a--}}
{{--                                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                    href="{{ route('manage.people.users.referral', [ 'id' => $user->id ]) }}"--}}
{{--                                    title="View"--}}
{{--                                >--}}
{{--                                    <i class="la la-eye"></i>--}}
{{--                                </a>--}}
{{--                                <a--}}
{{--                                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                    href="{{ route('manage.people.users.edit', [ 'id' => $user->id ]) }}"--}}
{{--                                    title="Edit"--}}
{{--                                >--}}
{{--                                    <i class="la la-edit"></i>--}}
{{--                                </a>--}}
{{--                                <a--}}
{{--                                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                    href="{{ route('manage.people.users.edit-password', [ 'id' => $user->id ]) }}"--}}
{{--                                    title="Change Password"--}}
{{--                                >--}}
{{--                                    <i class="la la-key"></i>--}}
{{--                                </a>--}}
{{--                                @if ($user->isActive())--}}
{{--                                    <button--}}
{{--                                        class="btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                        title="Ban"--}}
{{--                                        form="{{ $banUserFormId }}"--}}
{{--                                    >--}}
{{--                                        <i class="la la-lock"></i>--}}
{{--                                    </button>--}}
{{--                                    <form--}}
{{--                                        id="{{ $banUserFormId }}"--}}
{{--                                        class="m-form d-none"--}}
{{--                                        method="post"--}}
{{--                                        action="{{ route('manage.people.users.ban', [ 'id' => $user->id ]) }}"--}}
{{--                                        data-confirm="true"--}}
{{--                                        data-confirm-type="warning"--}}
{{--                                        data-confirm-title="Ban <strong>{{ $user->profile->full_name }}</strong>"--}}
{{--                                        data-confirm-text="You are about to ban this user."--}}
{{--                                    >--}}
{{--                                        @method('post')--}}
{{--                                        @csrf--}}
{{--                                    </form>--}}
{{--                                @endif--}}
{{--                                @if ($user->isBanned())--}}
{{--                                    <button--}}
{{--                                        class="btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                        title="Unban"--}}
{{--                                        form="{{ $unbanUserFormId }}"--}}
{{--                                    >--}}
{{--                                        <i class="la la-unlock-alt"></i>--}}
{{--                                    </button>--}}
{{--                                    <form--}}
{{--                                        id="{{ $unbanUserFormId }}"--}}
{{--                                        class="m-form d-none"--}}
{{--                                        method="post"--}}
{{--                                        action="{{ route('manage.people.users.unban', [ 'id' => $user->id ]) }}"--}}
{{--                                        data-confirm="true"--}}
{{--                                        data-confirm-type="warning"--}}
{{--                                        data-confirm-title="Unban <strong>{{ $user->profile->full_name }}</strong>"--}}
{{--                                        data-confirm-text="You are about to unban this user."--}}
{{--                                    >--}}
{{--                                        @method('delete')--}}
{{--                                        @csrf--}}
{{--                                    </form>--}}
{{--                                @endif--}}
{{--                                <button--}}
{{--                                    class="btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                    title="Delete"--}}
{{--                                    form="{{ $deleteUserFormId }}"--}}
{{--                                >--}}
{{--                                    <i class="la la-trash"></i>--}}
{{--                                </button>--}}
{{--                                <form--}}
{{--                                    id="{{ $deleteUserFormId }}"--}}
{{--                                    class="m-form d-none"--}}
{{--                                    method="post"--}}
{{--                                    action="{{ route('manage.people.users.destroy', [ 'id' => $user->id ]) }}"--}}
{{--                                    data-confirm="true"--}}
{{--                                    data-confirm-type="delete"--}}
{{--                                    data-confirm-title="Delete <strong>{{ $user->profile->full_name }}</strong>"--}}
{{--                                    data-confirm-text="You are about to delete this user, this procedure is irreversible."--}}
{{--                                >--}}
{{--                                    @method('delete')--}}
{{--                                    @csrf--}}
{{--                                </form>--}}
{{--                            @endif--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--                <tfoot>--}}
{{--                <tr>--}}
{{--                    <td colspan="6">--}}
{{--                        <div class="d-flex justify-content-between align-items-center mt-3">--}}
{{--                            @include('manage.components.pagination', [ 'results' => $users ])--}}
{{--                            @include('manage.components.pagination-counter', ['results' => $users])--}}
{{--                        </div>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                </tfoot>--}}
{{--            </table>--}}
{{--        </div>--}}

{{--    @endcomponent--}}
{{--@endsection--}}

{{--@push('quick-sidebar')--}}
{{--    @component('manage.components.table-filter-quick-sidebar', [--}}
{{--        'id' => 'users-filter-quick-sidebar',--}}
{{--        'user_id'=> $user_id--}}
{{--    ])--}}
{{--        <fieldset class="m-form__section m-form__section--first">--}}
{{--            <div class="form-group m-form__group">--}}
{{--                <label>Search</label>--}}
{{--                <input--}}
{{--                    type="text"--}}
{{--                    class="form-control m-input"--}}
{{--                    name="search"--}}
{{--                    value="{{ request('search') }}"--}}
{{--                >--}}
{{--            </div>--}}

{{--            <div class="form-group m-form__group">--}}
{{--                <label>Status</label>--}}
{{--                <div class="m-checkbox-list">--}}
{{--                    <label class="m-checkbox">--}}
{{--                        <input--}}
{{--                            type="checkbox"--}}
{{--                            name="status[]"--}}
{{--                            value="{{ \Src\People\User::STATUS_ACTIVE }}"--}}
{{--                            {{ in_array(\Src\People\User::STATUS_ACTIVE, request('status', [])) ? 'checked' : '' }}--}}
{{--                        >--}}
{{--                        Active--}}
{{--                        <span></span>--}}
{{--                    </label>--}}
{{--                    <label class="m-checkbox">--}}
{{--                        <input--}}
{{--                            type="checkbox"--}}
{{--                            name="status[]"--}}
{{--                            value="{{ \Src\People\User::STATUS_BANNED }}"--}}
{{--                            {{ in_array(\Src\People\User::STATUS_BANNED, request('status', [])) ? 'checked' : '' }}--}}
{{--                        >--}}
{{--                        Banned--}}
{{--                        <span></span>--}}
{{--                    </label>--}}
{{--                </div>--}}
{{--                <span class="m-form__help">Status of user</span>--}}
{{--            </div>--}}
{{--        </fieldset>--}}
{{--    @endcomponent--}}
{{--@endpush--}}
