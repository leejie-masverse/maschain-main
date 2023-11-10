@extends('manage.layouts.admin', [
    'pageTitle' => 'Your personal info',
    'pageSubtitle' => 'Manage your basic information — your name, email, and phone number — to help others find you on IPM, and make it easier to get in touch.'
])

@section('content')
    <div class="row">
        <div class="col-lg-9">
            @component('manage.components.portlet', [
                'headText' => 'User',
                'headIcon' => 'flaticon-user',
                'formAction' => route('manage.account.profile.update'),
                'formFiles' => true,
                'formMethod' => 'patch',
            ])
                <div class="row">
                    <div class="col-md-3 order-md-1">
                        <fieldset class="m-form__section m-form__section--first">
                            <div class="form-group m-form__group {{ $errors->has('portrait') ? 'has-danger' : '' }}">
                                <label class="form-control-label">Portrait</label>
                                <div
                                    class="file-input-preview file-input-preview--sm mx-auto "
                                    data-size-ratio="1"
                                >
                                    <img
                                        src="{{ $user->portrait->url }}"
                                        data-placeholder="http://via.placeholder.com/480x480"
                                    >
                                </div>
                                <input
                                    type="file"
                                    class="d-none"
                                    name="portrait"
                                    accept="image/gif, image/jpeg, image/png"
                                    data-file-preview
                                >
                                @include('manage.components.form-control-feedback', [ 'field' => 'portrait' ])
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-9">
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
            @endcomponent
        </div>
    </div>
@endsection
