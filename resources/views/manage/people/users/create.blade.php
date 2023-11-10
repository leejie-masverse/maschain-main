@extends('manage.layouts.admin', [ 'pageTitle' => 'Add User' ])

@section('content')
    <div class="row">
        <div class="col-lg-9">
            @component('manage.components.portlet', [
                'headText' => 'User',
                'headIcon' => 'flaticon-user',
                'formAction' => route('manage.people.users.store'),
                'formFiles' => true,
            ])
                @php
                    $authedUserAccessibleRoles = auth()->guard('web')->user()->getAccessibleRoles();
                    $roles = \Src\Auth\Role::whereIn('name', $authedUserAccessibleRoles)->get()
                @endphp

                <div class="row">
{{--                    <div class="col-md-3 order-md-1">--}}
{{--                        <fieldset class="m-form__section m-form__section--first">--}}
{{--                            <div class="form-group m-form__group {{ $errors->has('portrait') ? 'has-danger' : '' }}">--}}
{{--                                <label class="form-control-label">Portrait</label>--}}
{{--                                <div--}}
{{--                                    class="file-input-preview file-input-preview--sm mx-auto "--}}
{{--                                    data-size-ratio="1"--}}
{{--                                >--}}
{{--                                    <img--}}
{{--                                        src="http://via.placeholder.com/480x480"--}}
{{--                                        data-placeholder="http://via.placeholder.com/480x480"--}}
{{--                                    >--}}
{{--                                </div>--}}
{{--                                <input--}}
{{--                                    type="file"--}}
{{--                                    class="d-none"--}}
{{--                                    name="portrait"--}}
{{--                                    accept="image/gif, image/jpeg, image/png"--}}
{{--                                    data-file-preview--}}
{{--                                >--}}
{{--                                @include('manage.components.form-control-feedback', [ 'field' => 'portrait' ])--}}
{{--                            </div>--}}
{{--                        </fieldset>--}}
{{--                    </div>--}}
                    <div class="col-md-12">
                        <fieldset class="m-form__section m-form__section--first">
                            <div class="form-group m-form__group {{ $errors->has('full_name') ? 'has-danger' : '' }}">
                                <label class="form-control-label">Full name *</label>
                                <input
                                    type="text"
                                    name="full_name"
                                    class="form-control"
                                    value="{{ old('full_name') }}"
                                >
                                @include('manage.components.form-control-feedback', [ 'field' => 'full_name' ])
                            </div>
                            <div class="form-group m-form__group {{ $errors->has('email') ? 'has-danger' : '' }}">
                                <label class="form-control-label">Email *</label>
                                <input
                                    type="text"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email') }}"
                                >
                                @include('manage.components.form-control-feedback', [ 'field' => 'email' ])
                            </div>
{{--                            <div class="form-group m-form__group {{ $errors->has('role') ? 'has-danger' : '' }}">--}}
{{--                                <label class="form-control-label">Role *</label>--}}
{{--                                <div class="m-radio-inline">--}}
{{--                                    @foreach($roles as $role)--}}
{{--                                        <label class="m-radio m-radio--solid m-radio--brand">--}}
{{--                                            <input--}}
{{--                                                type="radio"--}}
{{--                                                name="role"--}}
{{--                                                value="{{ $role->name }}"--}}
{{--                                                {{ old('role', $loop->first ? $role->name : null) === $role->name ? 'checked' : '' }}--}}
{{--                                            >--}}
{{--                                            {{ $role->name }}--}}
{{--                                            <span></span>--}}
{{--                                        </label>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                                @include('manage.components.form-control-feedback', [ 'field' => 'role' ])--}}
{{--                            </div>--}}

                        <div class="m-separator m-separator--dashed m-separator--lg"></div>

                        <fieldset class="m-form__section">
                            <div class="form-group m-form__group row">
                                <div class="col-sm-6 m-form__group-sub {{ $errors->has('password') ? 'has-danger' : '' }}">
                                    <label class="form-control-label">New Password *</label>
                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control m-input"
                                    >
                                    <p class="m-form__help">Your password must be at least 8 characters long. To make it stronger, use upper and lower case letters, numbers and symbols. Space( ), quote(\', ") and slash(\) aren\'t allowed.</p>
                                    @include('manage.components.form-control-feedback', [ 'field' => 'password' ])
                                </div>
                                <div class="col-sm-6 m-form__group-sub {{ $errors->has('password_confirmation') ? 'has-danger' : '' }}">
                                    <label class="form-control-label">Password Confirmation *</label>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        class="form-control"
                                    >
                                    <p class="m-form__help">Retype the new password you entered.</p>
                                    @include('manage.components.form-control-feedback', [ 'field' => 'password_confirmation' ])
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                @slot('formActionsLeft')
                    <button
                        type="submit"
                        class="btn btn-brand"
                    >
                        Submit
                    </button>
                    <button
                        type="reset"
                        class="btn btn-secondary"
                    >
                        Reset
                    </button>
                @endslot

                @slot('formActionsRight')
                    <a
                        class="btn btn-link m-link m--font-bold text-muted"
                        href="{{ route('manage.people.users.list') }}"
                    >
                        Cancel
                    </a>
                @endslot
            @endcomponent
        </div>
    </div>
@endsection

@push('style')
    .portrait-file-input-preview {

    }
@endpush
