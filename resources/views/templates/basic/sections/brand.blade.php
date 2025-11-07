@php
    $brandElements = getContent('brand.element');
@endphp

<section class="product-brand-section ptb-60 brand-fullwidth">
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-12">
                <div class="brand-slider">
                    <div class="swiper-wrapper">
                        @foreach ($brandElements as $brandElement)
                            @if ($loop->odd)
                                <div class="swiper-slide">
                                    <div class="brand-item">
                                        <img src="{{ frontendImage('brand', @$brandElement->data_values->image, '170x100') }}" alt="brand">
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="brand-slider-two mt-2">
                    <div class="swiper-wrapper">
                        @foreach ($brandElements as $brandElement)
                            @if ($loop->even)
                                <div class="swiper-slide">
                                    <div class="brand-item">
                                        <img src="{{ frontendImage('brand', @$brandElement->data_values->image, '170x100') }}" alt="brand">
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
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

@push('script')
    <script>
        (function($) {
            "use strict";

            var swiper2 = new Swiper('.brand-slider-two', {
                slidesPerView: 6,
                spaceBetween: 16,
                loop: true,
                autoplay: {
                    speed: 1000,
                    delay: 3000,
                },
                speed: 800,
                breakpoints: {
                    1400: { slidesPerView: 8 },
                    1200: { slidesPerView: 7 },
                    992:  { slidesPerView: 6 },
                    768:  { slidesPerView: 5 },
                    576:  { slidesPerView: 4 },
                    0:    { slidesPerView: 3 },
                }
            });

            var swiper1 = new Swiper('.brand-slider', {
                slidesPerView: 6,
                spaceBetween: 16,
                loop: true,
                autoplay: {
                    speed: 1000,
                    delay: 3000,
                },
                speed: 800,
                breakpoints: {
                    1400: { slidesPerView: 8 },
                    1200: { slidesPerView: 7 },
                    992:  { slidesPerView: 6 },
                    768:  { slidesPerView: 5 },
                    576:  { slidesPerView: 4 },
                    0:    { slidesPerView: 3 },
                }
            });

        })(jQuery);
    </script>
@endpush
