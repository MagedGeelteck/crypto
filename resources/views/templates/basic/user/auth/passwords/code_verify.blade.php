@extends($activeTemplate . 'layouts.frontend')
<section class="pb-60">
    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <div class="d-flex justify-content-center">
                        <div class="verification-code-wrapper">
                            <div class="verification-area">
                                <h5 class="border-bottom pb-3 text-center">@lang('Verify Email Address')</h5>
                                <form class="submit-form mb-0" action="{{ route('user.password.verify.code') }}" method="POST">
                                    @csrf
                                    <p class="verification-text">@lang('A 6 digit verification code sent to your email address') : {{ showEmailAddress($email) }}</p>
                                    <input name="email" type="hidden" value="{{ $email }}">
                                    @include($activeTemplate . 'partials.verification_code')
                                    <div class="form-group">
                                        <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                                    </div>

                                    <p class="mb-0">
                                        @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                        <a href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                                    </p>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
