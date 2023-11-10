@extends('manage.layouts.app', [ 'pageTitle' => 'Login' ])

@section('page')
    <div
        class="m-grid__item m-login m-login--signin center-align mt-5">
        <div class="m-grid__item w-75" style="margin: auto">
            <div class="m-stack m-stack--hor m-stack--desktop">
                <div class="m-stack__item m-stack__item--fluid center-align">
                    <div class="m-login__wrapper">
                        <div class="center-align text-center mb-5 mt-5" style="margin: auto">
                            <img src="/main/images/logo.png" style="width: 50%">
                        </div>
                        <section class="m-login__signin mt-5 w-50" style="margin: auto">
{{--                            <header class="m-login__head">--}}
{{--                                <h3 class="m-login__title">Sign In To Admin</h3>--}}
{{--                            </header>--}}
                            <form
                                method="post"
                                class="m-login__form m-form"
                                action="{{ route('manage.account.login') }}"
                            >
                                @csrf
                                <div class="m-form__content">
                                    @include('flash::message')
                                </div>
                                <fieldset class="m-form__seciont m-form__section--first">
                                    <div class="form-group m-form__group {{ $errors->has('email') ? 'has-danger' : '' }} center-align text-center">
                                        <input
                                            class="form-control m-input w-50"
                                            type="text"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="Email"
                                            autocomplete="off"
                                            autofocus
                                        >
                                        @include('manage.components.form-control-feedback', [ 'field' => 'email' ])
                                    </div>
                                    <div class="form-group m-form__group {{ $errors->has('password') ? 'has-danger' : '' }}">
                                        <input
                                            class="form-control m-input w-50"
                                            type="password"
                                            name="password"
                                            placeholder="Password"
                                        >
                                        @include('manage.components.form-control-feedback', [ 'field' => 'password' ])
                                    </div>
                                    <div class="row m-login__form-sub">
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
                                </fieldset>
                                <div class="m-login__form-action">
                                    <button
                                        type="submit"
                                        class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air float-right"
                                        :class="{'m-loader m-loader--right m-loader--light': busy }"
                                        :disabled="errors.any() || busy || notValidated"
                                    >
                                        Sign In
                                    </button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
{{--        <div--}}
{{--            class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1 m-login__content m-grid-item--center">--}}
{{--            <div class="m-grid__item m-grid__item--middle login-right-section">--}}
{{--                <h3 class="m-login__welcome">Join Our Community</h3>--}}
{{--                <p class="m-login__msg">--}}
{{--                    Lorem ipsum dolor sit amet, coectetuer adipiscing<br>--}}
{{--                    elit sed diam nonummy et nibh euismod--}}
{{--                </p>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@endsection
