@php
    $testimonialElements = getContent('testimonial.element');
@endphp

<section class="client-section ptb-60 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="client-slider">
                    <div class="swiper-wrapper">
                        @foreach ($testimonialElements as $testimonialElement)
                            <div class="swiper-slide">
                                <div class="client-item">
                                    <div class="client-content text-center">
                                        <div class="client-quote-icon">
                                            <i class="las la-quote-right"></i>
                                        </div>
                                        <p>{{ __(@$testimonialElement->data_values->quote) }}</p>
                                    </div>
                                    <div class="client-thumb-area d-flex justify-content-center align-items-center flex-wrap">
                                        <div class="client-thumb">
                                        </div>
                                        <div class="client-thumb-content">
                                            <h3 class="title">{{ __(@$testimonialElement->data_values->name) }}</h3>
                                            <span class="sub-title">{{ __(@$testimonialElement->data_values->designation) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

@push('script-lib')
    <script>window.SLIDER_TESTIMONIAL = true;</script>
@endpush
