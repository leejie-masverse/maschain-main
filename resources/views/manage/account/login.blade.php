@extends('manage.layouts.app', [ 'pageTitle' => 'Login' ])

@section('page')
    <div class="container-login">
        <div class="wrap-login">
            <form
                method="post"
                class="m-login__form m-form login-form-vp"
                action="{{ route('manage.account.login') }}"
            >
                <div class="w-100">
                    <img src="{{ asset('manage/images/logo.png') }}" class="login-logo">
                </div>
                <span class="login-form-vp-title pb-5">
					ADMIN ACCESS<br/>
				</span>
                @csrf
                <div class="m-form__content">
                    @include('flash::message')
                </div>

                <div class="mb-4">
                    <input
                        class="vp-input login-form-vp-input"
                        type="text"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Email"
                        autocomplete="off"
                        autofocus
                    >
                    <div class="form-group m-form__group {{ $errors->has('email') ? 'has-danger' : '' }} center-align text-center" >
                        @include('manage.components.form-control-feedback', [ 'field' => 'email' ])
                    </div>
                </div>

                <div class="mb-4">
                    <input
                        class="vp-input login-form-vp-input"
                        type="password"
                        name="password"
                        placeholder="Password"
                    >
                    <div class="form-group m-form__group {{ $errors->has('password') ? 'has-danger' : '' }} center-align text-center" >
                        @include('manage.components.form-control-feedback', [ 'field' => 'password' ])
                    </div>
                </div>

                <div class="row m-login__form-sub ml-1 mb-5">
                    <div class="col">
                        <label class="m-checkbox m-checkbox--focus">
                            <input
                                type="checkbox"
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            Remember me
                            <span class="m-checkbox__indicator"></span>
                        </label>
                    </div>
                </div>

                <button class="login-form-vp-btn" type="submit">Sign In</button>
            </form>
        </div>
    </div>
@endsection

@section('style')
    <link href="{{ asset('manage/app.css') }}" rel="stylesheet">
@stop
