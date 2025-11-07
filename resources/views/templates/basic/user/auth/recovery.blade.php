@extends($activeTemplate . 'layouts.frontend')

@section('panel')
<section class="account">
    <div class="account-inner">
        <div class="account-inner__right w-100">
            <div class="account-form-wrapper">
                <a href="{{ route('home') }}" class="account-form__logo">
                    <img src="{{ siteLogo() }}" alt="logo">
                </a>
                <h5 class="account-form__title text-center">@lang('Password Recovery')</h5>
                <p class="text-center mb-3 small text-muted">@lang('Reset your password using your username and recovery word.')</p>
                <form method="POST" action="{{ route('user.password.recovery.submit') }}" class="w-100">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="username">@lang('Username')</label>
                        <input type="text" class="form-control form--control" id="username" name="username" value="{{ old('username') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="recovery_word">@lang('Recovery Word')</label>
                        <input type="text" class="form-control form--control" id="recovery_word" name="recovery_word" autocomplete="off" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="password">@lang('New Password')</label>
                                <input type="password" class="form-control form--control @if (gs('secure_password')) secure-password @endif" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="password_confirmation">@lang('Confirm Password')</label>
                                <input type="password" class="form-control form--control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn--base w-100" type="submit">@lang('Reset Password')</button>
                    </div>
                    <p class="any-account mb-0 text-center"><a href="{{ route('user.login') }}">@lang('Back to login')</a></p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
