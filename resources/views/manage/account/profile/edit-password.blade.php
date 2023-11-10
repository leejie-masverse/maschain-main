@extends('manage.layouts.admin', [
    'pageTitle' => 'Change password',
    'pageSubtitle' => 'Control your password, and make sure you choose a strong password.'
])

@section('content')
    <div class="row">
        <div class="col-lg-9">
            @component('manage.components.portlet', [
                'headText' => 'Password',
                'headIcon' => 'flaticon-lock',
                'formAction' => route('manage.account.profile.update-password'),
                'formMethod' => 'patch',
            ])
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
            @endcomponent
        </div>
    </div>
@endsection
