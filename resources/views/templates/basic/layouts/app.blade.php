<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    @include('partials.seo')

    {{-- Canonical URL for SEO --}}
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Sitemap link for crawlers --}}
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    
    {{-- Alternate link for Tor indexing --}}
    <link rel="alternate" type="application/onion" href="http://mtidtmncruzy4k3p5jhthhsmm3vohsxxb2vayjicntykoy4lwcl7gvqd.onion{{ request()->getRequestUri() }}">

    @unless(env('ONION_HOST'))
        <!-- Preconnect and load Oswald font from Google Fonts for non-onion environments -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Request explicit weights for Oswald so Google serves the proper font files -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    @endunless

    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/line-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">

    @stack('style-lib')

    <link href="{{ asset($activeTemplateTrue . 'css/main.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/custom.css') }}" rel="stylesheet">

    @stack('style')

    <link href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}" rel="stylesheet">

    </head>

    @unless(env('ONION_HOST'))
        @php echo loadExtension('google-analytics') @endphp
    @endunless

    <body>

    @stack('fbComment')

    <div class="preloader">
        <div class="surface">
            <div class="coin"><i class="fab fa-bitcoin"></i></div>
            <div class="shadow"></div>
        </div>
    </div>

    <a class="scrollToTop" href="#"><i class="las la-angle-double-up"></i></a>

    <div class="page-wrapper">

        <div class="main-section">

            @yield('panel')
            
        </div>
    </div>

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>

    @stack('script-lib')

    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/sliders-init.js') }}"></script>



    @unless(env('ONION_HOST'))
        @php echo loadExtension('tawk-chat') @endphp
    @endunless

    @include('partials.notify')

    @if (gs('pn') && !env('ONION_HOST'))
        @include('partials.push_script')
    @endif

    @stack('script')

    <script>
        (function($) {
            "use strict";

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });

            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    let columArray = Array.from(row.querySelectorAll('td'));
                    if (columArray.length <= 1) return;
                    columArray.forEach((colum, i) => {
                        colum.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });

        })(jQuery);
    </script>

</body>

</html>
