@extends('manage.layouts.admin', [ 'pageTitle' => "Edit Admin #{$user->id}" ])

@section('content')
    <div class="row">
        <div class="col-lg-9">
            @component('manage.components.portlet', [
                'headText' => 'Admin',
                'headIcon' => 'flaticon-user',
                'formAction' => route('manage.people.admins.update', ['id' => $user->id]),
                'formFiles' => true,
                'formMethod' => 'patch',
            ])
                @php
                    $roles = \Src\Auth\Role::getAdminRoles()
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
{{--                                        src="{{ $user->portrait->url }}"--}}
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
                                    value="{{ old('full_name', $user->profile->full_name) }}"
                                >
                                @include('manage.components.form-control-feedback', [ 'field' => 'full_name' ])
                            </div>
                            <div class="form-group m-form__group {{ $errors->has('email') ? 'has-danger' : '' }}">
                                <label class="form-control-label">Email *</label>
                                <input
                                    type="text"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email', $user->email) }}"
                                >
                                @include('manage.components.form-control-feedback', [ 'field' => 'email' ])
                            </div>
                            <div class="form-group m-form__group {{ $errors->has('role') ? 'has-danger' : '' }}">
                                <label class="form-control-label">Role *</label>
                                <div class="m-radio-inline">
                                    @foreach($roles as $role)
                                        <label class="m-radio m-radio--solid m-radio--brand">
                                            <input
                                                type="radio"
                                                name="role"
                                                class="form_admin_role"
                                                value="{{ $role->name }}"
                                                {{ old('role', $user->role->name) === $role->name ? 'checked' : '' }}
                                            >
                                            {{ $role->name }}
                                            <span></span>
                                        </label>
                                    @endforeach
                                </div>
                                @include('manage.components.form-control-feedback', [ 'field' => 'role' ])
                            </div>

                            <div class="m-separator m-separator--dashed m-separator--lg"></div>
                            @php
                                $oldAbilitiies = old('abilities') !== null ? old('abilities') : $user->getAbilities()->pluck('name')->toArray();
                            @endphp

                            <fieldset class="m-form__section" id="form_admin_abilities_section" style="display: none">
                                <div class="form-group m-form__group">
                                    <div class="{{ $errors->has('abilities') ? 'has-danger' : '' }}">
                                        <label class="form-control-label">Admin Abilities *</label>
                                        <div class="row">
                                            @foreach($abilities as $ability)
                                                <div class="col-sm-4 m-form__group-sub">
                                                    <input
                                                        type="checkbox"
                                                        name="abilities[]"
                                                        value="{{ $ability }}"
                                                        id="{{ $ability }}"
                                                        {{ $oldAbilitiies!== null && in_array($ability, $oldAbilitiies) ? 'checked' : '' }}
                                                    >
                                                    <label class="form-control-label capitalize" for="{{ $ability }}">{{ $ability }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @include('manage.components.form-control-feedback', [ 'field' => 'abilities' ])
                                </div>
                            </fieldset>
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
                        href="{{ route('manage.people.admins.list') }}"
                    >
                        Cancel
                    </a>
                @endslot
            @endcomponent
        </div>
    </div>
@endsection
