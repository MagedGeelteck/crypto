@php
    $bannerElements = getContent('banner.element', orderById: true);
@endphp

<section class="banner-section banner-fullwidth ptb-30">
    <div class="container">
        <div class="banner-slider">
        <div class="swiper-wrapper">
            @foreach ($bannerElements as $bannerElement)
                <div class="swiper-slide">
                    <div class="banner-thumb-area">
                        <div class="banner-thumb">
                            <img src="{{ frontendImage('banner', @$bannerElement->data_values->image, '1920x420') }}" alt="banner">
                        </div>
                        <div class="banner-content">
                            <h3 hidden class="sub-title text-white">{{ __(@$bannerElement->data_values->upper_title) }}</h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

@if (!app()->offsetExists('slider_style'))
    @push('style-lib')
        <link href="{{ asset($activeTemplateTrue . 'css/swiper.min.css') }}" rel="stylesheet">
    @endpush
    @php app()->offsetSet('slider_style',true) @endphp
@endif

@if (!app()->offsetExists('slider_script'))
    @push('script-lib')
        <script src="{{ asset($activeTemplateTrue . 'js/swiper.min.js') }}"></script>
    @endpush
    @php app()->offsetSet('slider_script',true) @endphp
@endif

@push('script-lib')
    <script>window.SLIDER_BANNER = true;</script>
@endpush
