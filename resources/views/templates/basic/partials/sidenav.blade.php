<aside class="sidebar-home col-xl-3 mobile-sidebar mb-30">
    <div class="aside-inner">
        <div class="side-menu-wrapper mb-30">
            <h3 class="side-menu-title">@lang('User Menu')</h3>
            <ul class="side-menu pt-2 mb-2 px-2 mx-3">
                <li>
                    <a href="{{route('user.home')}}">@lang('Dashboard')</a>
                </li>
                <li>
                    <a href="{{route('user.purchase.log')}}">@lang('Purchase Log')</a>
                </li>
                <li>
                    <a href="{{route('ticket.index')}}">@lang('Support Tickets')</a>
                </li>
            </ul>
        </div>
    </div>
</aside>
<div class="sidebar-toggle"><i class="fas fa-sliders-h"></i></div>
