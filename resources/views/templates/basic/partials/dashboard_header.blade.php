<div class="body-header-area d-flex align-items-center justify-content-between flex-wrap">
    <div class="body-header-left">
        <h3 class="title">{{ __($pageTitle) }}</h3>
    </div>
    <div class="body-header-right dropdown">
        <button class="" data-toggle="dropdown" data-display="static" type="button" aria-haspopup="true" aria-expanded="false">
            <div class="header-user-area d-flex align-items-center justify-content-between flex-wrap">
                <div class="header-user-thumb">
                    <a href="javascript:void(0)"><img src="{{ getImage(getFilePath('userProfile') . '/' . auth()->user()->image, getFileSize('userProfile'), true) }}" alt="user"></a>
                </div>
                <div class="header-user-content">
                    <span>{{ auth()->user()->fullname }}</span>
                </div>
                <span class="header-user-icon"><i class="las la-chevron-circle-down"></i></span>
            </div>
        </button>
        <div class="dropdown-menu dropdown-menu--sm dropdown-menu-right border-0 p-0">
            <a class="dropdown-menu__item d-flex align-items-center px-3 py-2" href="{{ route('user.change.password') }}">
                <i class="dropdown-menu__icon las la-user-circle"></i>
                <span class="dropdown-menu__caption">@lang('Change Password')</span>
            </a>
            <a class="dropdown-menu__item d-flex align-items-center px-3 py-2" href="{{ route('user.profile.setting') }}">
                <i class="dropdown-menu__icon las la-user-circle"></i>
                <span class="dropdown-menu__caption">@lang('Profile Settings')</span>
            </a>
            <a class="dropdown-menu__item d-flex align-items-center px-3 py-2" href="{{ route('user.twofactor') }}">
                <i class="dropdown-menu__icon las la-user-circle"></i>
                <span class="dropdown-menu__caption">@lang('2FA Security')</span>
            </a>
            <a class="dropdown-menu__item d-flex align-items-center px-3 py-2" href="{{ route('user.logout') }}">
                <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                <span class="dropdown-menu__caption">@lang('Logout')</span>
            </a>
        </div>
    </div>
</div>
