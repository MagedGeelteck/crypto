@extends($activeTemplate . 'layouts.frontend')

@php
    $loginContent = getContent('login.content', true);
@endphp

@section("panel")
    <section class="account">
        <div class="account-inner">
            <div class="account-inner__left">
                <div class="account-thumb">
                    <img src="{{ frontendImage('login', $loginContent->data_values->image) }}" alt="login">
                </div>
            </div>
            <div class="account-inner__right">
                <div class="account-form-wrapper">
                    <a href="{{ route('home') }}" class="account-form__logo">
                        <img src="{{ siteLogo() }}" alt="logo">
                    </a>
                    <h5 class="account-form__title text-center">@lang("Login")</h5>
                    <p class="text-center mb-3 small text-muted">@lang('Use your username and password to access your account')</p>
                    {{-- Social login removed as per request --}}
                    <form class="verify-gcaptcha w-100" method="POST" action="{{ route("user.login") }}">
                        @csrf

                        <div class="form-group">
                            <label class="form-label" for="username">@lang("Username")</label>
                            <input class="form-control form--control" id="username" name="username" type="text" value="{{ old("username") }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">@lang("Password")</label>
                            <input class="form-control form--control" id="password" name="password" type="password" required>
                        </div>

                        <x-captcha />

                        <div class="form-group custom-check-group">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <input class="form-check-input" id="remember" name="remember" type="checkbox" {{ old("remember", true) ? "checked" : "" }}>
                                    <label class="form-check-label mb-0" for="remember">
                                        @lang("Remember Me")
                                    </label>
                                </div>
                                <div>
                                    <a class="forgot-pass" href="{{ route("user.password.request") }}">
                                        @lang("Forgot your password?")
                                    </a>
                                    <a class="forgot-pass ms-2" href="{{ route('user.password.recovery') }}">
                                        @lang('Recovery word reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn--base w-100" id="recaptcha" type="submit">
                                @lang("Login")
                            </button>
                        </div>
                        <p class="any-account mb-0">@lang('Don\'t have any account?') <a href="{{ route("user.register") }}">@lang("Register")</a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
