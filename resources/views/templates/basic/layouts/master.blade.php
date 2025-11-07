@extends($activeTemplate . 'layouts.app')
@section('panel')
    @include('Template::partials.header')

    @include($activeTemplate . 'partials.breadcrumb')

    <section class="pb-60">
        <div class="container">
            <div class="sidebar-overlay"></div>
            <div class="row">
                @include($activeTemplate . 'partials.sidenav')

                <div class="col-xl-9 mb-30">
                    <div class="body-wrapper">

                        @include($activeTemplate . 'partials.dashboard_header')

                        @yield('content')

                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('Template::partials.footer')
    
@endsection