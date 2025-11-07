@php
    $text = isset($register) ? 'Register' : 'Login';
@endphp
@if (@gs('socialite_credentials')->google->status == Status::ENABLE)
<div class=" flex-grow-1 continue-google">
    <a href="{{ route('user.social.login', 'google') }}" class="btn w-100 social-login-btn">
        <span class="google-icon">
        <img src="{{ asset($activeTemplateTrue."images/google.svg") }}" alt="Google">
        </span> @lang("Google")
    </a>
</div>
@endif
@if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
<div class=" flex-grow-1 continue-facebook">
    <a href="{{ route('user.social.login', 'facebook') }}" class="btn w-100 social-login-btn">
        <span class="facebook-icon">
        <img src="{{ asset($activeTemplateTrue."images/facebook.svg") }}" alt="Facebook">
        </span> @lang("Facebook")
    </a>
</div>
@endif
@if (@gs('socialite_credentials')->linkedin->status == Status::ENABLE)
<div class="continue-facebook flex-grow-1">
    <a href="{{ route('user.social.login', 'linkedin') }}" class="btn w-100 social-login-btn">
        <span class="facebook-icon">
        <img src="{{ asset($activeTemplateTrue."images/linkdin.svg") }}" alt="Linkedin">
        </span> @lang("Linkedin")
    </a>
</div>
@endif

@if (@gs('socialite_credentials')->linkedin->status || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->google->status == Status::ENABLE)
<div class="another-login text-center mb-3">
    <span class="text-white text">@lang('OR')</span>
</div>
@endif