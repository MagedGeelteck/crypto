@php
    $serviceElements = getContent('service.element', orderById: true);
@endphp

<section class="info-section section--bg ptb-20">
    <div class="container">
        <div class="info-area">
            <div class="info-slider">
                <div class="swiper-wrapper">
                    @foreach ($serviceElements as $serviceElement)
                        <div class="swiper-slide">
                            <div class="info-item d-flex justify-content-center align-items-center flex-wrap text-white">
                                <div class="info-icon">
                                    @php echo @$serviceElement->data_values->icon @endphp
                                </div>
                                <div class="info-content">
                                    <h4 class="text-white">{{ __(@$serviceElement->data_values->heading) }}</h4>
                                    <p>{{ __(@$serviceElement->data_values->sub_title) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
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

            var swiper = new Swiper('.info-slider', {
                slidesPerView: 3,
                spaceBetween: 0,
                loop: true,
                autoplay: {
                    speed: 1000,
                    delay: 3000,
                },
                speed: 1000,
                breakpoints: {
                    991: {
                        slidesPerView: 2,
                    },
                    767: {
                        slidesPerView: 1,
                    },
                    575: {
                        slidesPerView: 1,
                    },
                }
            });

        })(jQuery);
    </script>
@endpush
