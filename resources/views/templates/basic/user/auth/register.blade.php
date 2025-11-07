@extends($activeTemplate . 'layouts.app')

@php
    $registerContent = getContent('register.content', true);
@endphp
@section('panel')
    <section class="account">
        <div class="account-inner">
            <div class="account-inner__left">
                <div class="account-thumb">
                    <img src="{{ frontendImage('register', $registerContent->data_values->image) }}" alt="register">
                </div>
            </div>
            <div class="account-inner__right">
                <div class="account-form-wrapper @if (!gs('registration')) form-disabled @endif">
                    @if (!gs('registration'))
                        <span class="form-disabled-text">
                            <svg class="" style="enable-background:new 0 0 512 512" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="80" height="80" x="0" y="0" viewBox="0 0 512 512" xml:space="preserve">
                                <g>
                                    <path class="" data-original="#f99f0b" d="M255.999 0c-79.044 0-143.352 64.308-143.352 143.353v70.193c0 4.78 3.879 8.656 8.659 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193c0-42.998 34.981-77.98 77.979-77.98s77.979 34.982 77.979 77.98v70.193c0 4.78 3.88 8.656 8.661 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193C399.352 64.308 335.044 0 255.999 0zM382.04 204.89h-30.748v-61.537c0-52.544-42.748-95.292-95.291-95.292s-95.291 42.748-95.291 95.292v61.537h-30.748v-61.537c0-69.499 56.54-126.04 126.038-126.04 69.499 0 126.04 56.541 126.04 126.04v61.537z" fill="#f99f0b" opacity="1"></path>
                                    <path class="" data-original="#f99f0b" d="M410.63 204.89H101.371c-20.505 0-37.188 16.683-37.188 37.188v232.734c0 20.505 16.683 37.188 37.188 37.188H410.63c20.505 0 37.187-16.683 37.187-37.189V242.078c0-20.505-16.682-37.188-37.187-37.188zm19.875 269.921c0 10.96-8.916 19.876-19.875 19.876H101.371c-10.96 0-19.876-8.916-19.876-19.876V242.078c0-10.96 8.916-19.876 19.876-19.876H410.63c10.959 0 19.875 8.916 19.875 19.876v232.733z" fill="#f99f0b" opacity="1"></path>
                                    <path class="" data-original="#f99f0b" d="M285.11 369.781c10.113-8.521 15.998-20.978 15.998-34.365 0-24.873-20.236-45.109-45.109-45.109-24.874 0-45.11 20.236-45.11 45.109 0 13.387 5.885 25.844 16 34.367l-9.731 46.362a8.66 8.66 0 0 0 8.472 10.436h60.738a8.654 8.654 0 0 0 8.47-10.434l-9.728-46.366zm-14.259-10.961a8.658 8.658 0 0 0-3.824 9.081l8.68 41.366h-39.415l8.682-41.363a8.655 8.655 0 0 0-3.824-9.081c-8.108-5.16-12.948-13.911-12.948-23.406 0-15.327 12.469-27.796 27.797-27.796 15.327 0 27.796 12.469 27.796 27.796.002 9.497-4.838 18.246-12.944 23.403z" fill="#f99f0b" opacity="1"></path>
                                </g>
                            </svg>
                        </span>
                    @endif
                    <a class="account-form__logo" href="{{ route('home') }}">
                        <img src="{{ siteLogo() }}" alt="logo">
                    </a>
                    <h5 class="account-form__title text-center">@lang('Register')</h5>
                    {{-- Social login removed as per request --}}

                    <form class="verify-gcaptcha disableSubmission w-100" action="{{ route('user.register') }}" method="POST">
                        @csrf
                        <div class="row">
                            @if (session()->get('reference') != null)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="referenceBy">@lang('Reference by')</label>
                                        <input class="form-control form--control" id="referenceBy" name="referBy" type="text" value="{{ session()->get('reference') }}" readonly>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('First Name')</label>
                                <input class="form-control form--control" name="firstname" type="text" value="{{ old('firstname') }}" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Last Name')</label>
                                <input class="form-control form--control" name="lastname" type="text" value="{{ old('lastname') }}" required>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Username')</label>
                                    <input class="form-control form--control checkUsername" name="username" type="text" value="{{ old('username') }}" required>
                                    <small class="text--danger usernameExist d-none"></small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Recovery Word') <small class="text-muted">(@lang('keep this secret'))</small></label>
                                    <input class="form-control form--control" name="recovery_word" type="text" value="{{ old('recovery_word') }}" minlength="4" maxlength="100" autocomplete="off">
                                    <small class="text-muted d-block">@lang('This word lets you reset your password without email. Do NOT share it.')</small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('E-Mail Address') <small class="text-muted">(@lang('optional'))</small></label>
                                    <input class="form-control form--control checkEmail" name="email" type="email" value="{{ old('email') }}">
                                    <small class="text--danger emailExist d-none"></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Password')</label>
                                    <input class="form-control form--control @if (gs('secure_password')) secure-password @endif" name="password" type="password" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Confirm Password')</label>
                                    <input class="form-control form--control" name="password_confirmation" type="password" required>
                                </div>
                            </div>

                            <x-captcha />

                        </div>

                        @if (gs('agree'))
                            @php
                                $policyPages = getContent('policy_pages.element', false, orderById: true);
                            @endphp
                            <div class="form-group custom-check-group">
                                <input id="agree" name="agree" type="checkbox" @checked(old('agree')) required>
                                <label for="agree">@lang('I agree with')</label> <span>
                                    @foreach ($policyPages as $policy)
                                        <a class="text--base" href="{{ route('policy.pages', $policy->slug) }}" target="_blank">{{ __($policy->data_values->title) }}</a>
                                        @if (!$loop->last)
                                            <span class="text--base">,</span>
                                        @endif
                                    @endforeach
                                </span>
                            </div>
                        @endif

                        <div class="form-group">
                            <button class="btn btn--base w-100" id="recaptcha" type="submit"> @lang('Register')</button>
                        </div>
                        <p class="any-account mb-0">@lang('Already have an account?') <a href="{{ route('user.login') }}">@lang('Login')</a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function($) {

            $('.checkEmail').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if(value.length){
                    var data = { email: value, _token: token }
                    $.post(url, data, function(response) {
                        if (response.data != false) {
                            $('.emailExist').text(`@lang('This email already exist')`).removeClass('d-none');
                        } else {
                            $('.emailExist').text('').addClass('d-none');
                        }
                    });
                } else {
                    $('.emailExist').text('').addClass('d-none');
                }
            });

            $('.checkUsername').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                var data = { username: value, _token: token }
                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $('.usernameExist').text(`@lang('This username is already taken')`).removeClass('d-none');
                    } else {
                        $('.usernameExist').text('').addClass('d-none');
                    }
                });
            });

            @if (!gs('registration'))
                notify('warning', 'Registration is currently disabled');
            @endif

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .form-group span {
            color: #ff5722;
        }

        .hover-input-popup .input-popup {
            bottom: 60px;
        }

        .form-disabled {
            overflow: hidden;
            position: relative;
        }

        .form-disabled::after {
            content: "";
            position: absolute;
            height: 100%;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            top: 0;
            left: 0;
            backdrop-filter: blur(2px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            z-index: 99;
        }

        .form-disabled-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 991;
            font-size: 24px;
            height: auto;
            width: 100%;
            text-align: center;
            color: hsl(var(--dark-600));
            font-weight: 800;
            line-height: 1.2;
        }
    </style>
@endpush
