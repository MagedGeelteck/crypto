@php
    // Product Collection: only products that belong to category named 'paypal' (case-insensitive)
    $products = App\Models\Product::active()
        ->whereHas('subcategory.category', function($q) {
            $q->whereRaw('LOWER(name) = ?', ['paypal']);
        })
        ->with('subcategory')
        ->latest()
        ->limit(12)
        ->get();
    $offerElements = getContent('home_page_offer.element');
    $subscriberElements = getContent('subscribe.content', true);

    $categories = App\Models\Category::active()
        ->hasProduct()
        ->with(['subcategories', 'products', 'products.subcategory'])
        ->latest()
        ->get();

    $featureCategories = App\Models\Category::active()
        ->hasFeatureProduct()
        ->with(['subcategories', 'products', 'products.subcategory'])
        ->latest()
        ->get();

    // Featured Products: only featured products that belong to category named 'custom' (case-insensitive)
    $featuredProducts = App\Models\Product::active()
        ->featured()
        ->whereHas('subcategory.category', function($q) {
            $q->whereRaw('LOWER(name) = ?', ['custom']);
        })
        ->with('subcategory')
        ->limit(9)
        ->latest()
        ->get();

@endphp

<main class="main-section">
    <section class="all-sections ptb-60">
        <div class="container">
            <div class="row mb-30-none">
                <div class="col-xl-9 col-lg-9 mb-30">
                    <div class="hot-deal pb-40">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="section-header">
                                    <h3 class="section-title">@lang('Paypal')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="product-slider">
                                    <div class="swiper-wrapper">
                                        @forelse($products as $item)
                                            <div class="swiper-slide">
                                                @include('Template::partials.product', ['item' => $item])
                                            </div>
                                        @empty
                                            <div class="col-xl-4 col-md-6 mb-30">
                                                <div class="product-default">
                                                    <div class="product-details d-flex align-items-start flex-wrap">
                                                        <h3 class="product-title">
                                                            @lang('Product not found')
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banners-group pb-40">
                        <div class="row mb-30-none">
                            <div class="col-md-6 mb-30">
                                <div class="product-banner">
                                    <figure>
                                        @php echo advertisements('580x240') @endphp
                                    </figure>

                                </div>
                            </div>
                            <div class="col-md-6 w-md-55 mb-30">
                                <div class="product-banner">
                                    <figure>
                                        @php echo advertisements('580x240') @endphp
                                    </figure>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-tab">
                        <ul class="nav nav-tabs mb-4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="featured-products-tab" data-bs-toggle="tab" href="#featured-products" role="tab" aria-controls="featured-products" aria-selected="true">@lang('Featured Products')</a>
                            </li>
                            @foreach ($featureCategories->take(6) as $item)
                                <li class="nav-item">
                                    <a class="nav-link" id="{{ str_replace(' ', '_', strtolower($item->name)) }}-tab" data-bs-toggle="tab" href="#{{ str_replace(' ', '_', strtolower($item->name)) }}" role="tab" aria-controls="{{ str_replace(' ', '_', strtolower($item->name)) }}" aria-selected="false">{{ __($item->name) }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="featured-products" role="tabpanel" aria-labelledby="featured-products-tab">
                                <div class="row mb-30-none">
                                    @forelse($featuredProducts as $item)
                                        <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
                                            <div class="product-default">
                                                <figure>
                                                    <a href="{{ route('product.details', [$item->id, slug($item->name)]) }}">
                                                        <img src="{{ getImage(getFilePath('product') . '/' . $item->images->first()?->name, getFileSize('product')) }}" alt="@lang('product')">
                                                    </a>

                                                    {{-- quick view removed per request --}}

                                                </figure>
                                                <div class="product-details d-flex align-items-start flex-wrap">
                                                    <div class="category-wrap d-flex justify-content-between align-items-center w-100 flex-wrap">
                                                        <div class="category-list">
                                                            <a class="product-category" href="{{ route('subcategory.search', $item->subcategory->id) }}">{{ __($item->subcategory->name) }}</a>
                                                        </div>

                                                    </div>
                                                    <h3 class="product-title">
                                                        <a href="{{ route('product.details', [$item->id, slug($item->name)]) }}">{{ __($item->name) }}</a>
                                                    </h3>
                                                    {{-- ratings removed from frontend section --}}
                                                    <div class="price-box">
                                                        @if ($item->old_price)
                                                            <span class="old-price">{{ showAmount($item->old_price) }}</span>
                                                        @endif
                                                        <span class="product-price">{{ showAmount($item->new_price) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-xl-4 col-md-6 mb-30">
                                            <div class="product-default">
                                                <div class="product-details d-flex align-items-start flex-wrap">
                                                    <h3 class="product-title">
                                                        @lang('Product not found')
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            @foreach ($featureCategories->take(6) as $data)
                                <div class="tab-pane fade" id="{{ str_replace(' ', '_', strtolower($data->name)) }}" role="tabpanel" aria-labelledby="{{ str_replace(' ', '_', strtolower($data->name)) }}-tab">
                                    <div class="row mb-30-none">

                                        @forelse ($data->products->take(15) as $item)
                                            <div class="col-xl-4 col-md-6 mb-30">
                                                <div class="product-default">
                                                    <figure>
                                                        <a href="{{ route('product.details', [$item->id, slug($item->name)]) }}">
                                                            <img src="{{ getImage(getFilePath('product') . '/' . $item->images->first()?->name, getFileSize('product')) }}" alt="@lang('product')">
                                                        </a>

                                                        {{-- quick view removed per request --}}
                                                    </figure>
                                                    <div class="product-details d-flex align-items-start flex-wrap">
                                                        <div class="category-wrap d-flex justify-content-between align-items-center w-100 flex-wrap">
                                                            <div class="category-list">
                                                                <a class="product-category" href="{{ route('subcategory.search', $item->subcategory->id) }}">{{ __($item->subcategory->name) }}</a>
                                                            </div>
                                                        </div>
                                                        <h3 class="product-title">
                                                            <a href="{{ route('product.details', [$item->id, slug($item->name)]) }}">{{ __($item->name) }}</a>
                                                        </h3>
                                                        {{-- ratings removed from frontend section --}}
                                                        <div class="price-box">
                                                            @if ($item->old_price)
                                                                <span class="old-price">{{ showAmount($item->old_price) }}</span>
                                                            @endif
                                                            <span class="product-price">{{ showAmount($item->new_price) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-xl-4 col-md-6 mb-30">
                                                <div class="product-default">
                                                    <div class="product-details d-flex align-items-start flex-wrap">
                                                        <h3 class="product-title">
                                                            @lang('Product not found')
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <aside class="sidebar-home col-xl-3 col-lg-3 mobile-sidebar mb-30">
                    <div class="aside-inner">
                        <div class="side-menu-wrapper mb-30">
                            <h3 class="side-menu-title">@lang('Categories')</h3>
                            <ul class="side-menu mx-3 mb-2 px-2 pt-2">
                                @forelse($categories as $item)
                                    <li class="has-sub">
                                        <a href="javascript:void(0)">{{ __($item->name) }}</a>

                                        @if (count($item->subcategories) > 0)
                                            <span class="side-menu-toggle"></span>

                                            <ul>
                                                @foreach ($item->subcategories as $data)
                                                    <li><a href="{{ route('subcategory.search', $data->id) }}">{{ __($data->name) }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @empty
                                    <li class="text-white">@lang('No data found')</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="widget widget-banners mb-30 px-4 pb-4 text-center">
                            <div class="widget-slider">
                                <div class="swiper-wrapper">

                                    @foreach ($offerElements as $item)
                                        <div class="swiper-slide">
                                            <div class="side-banner d-flex flex-column align-items-center">
                                                <h3 class="badge-sale bg--base d-flex flex-column align-items-center justify-content-center text-uppercase">
                                                    <em class="pt-2">{{ __(@$item->data_values->heading) }}</em>{{ __(@$item->data_values->sub_title) }}
                                                </h3>
                                                <h4 class="sale-text font1 text-uppercase m-b-3">{{ __(@$item->data_values->discount) }}<sup>%</sup><sub>@lang('off')</sub>
                                                </h4>
                                                <p>{{ __(@$item->data_values->details) }}</p>
                                                <a class="btn btn--base btn-md" href="{{ __(@$item->data_values->url) }}">@lang('View Sale')</a>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="widget widget-newsletters bg-gray mb-30 text-center" hidden>
                            <h3 class="widget-title text-uppercase">{{ @$subscriberElements->data_values->heading }}</h3>
                            <p class="mb-2">{{ @$subscriberElements->data_values->sub_title }} </p>
                            <form class="subscribe-form">
                                <div class="form-group position-relative envolope-letter mb-0">
                                    <input class="form-control" id="subscriber" name="email" type="email" placeholder="@lang('Email address')" required>
                                    <button class="btn--base subs mt-20" type="submit">@lang('Subscribe')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </aside>
                <div class="sidebar-overlay"></div>
                <div class="sidebar-toggle"><i class="fas fa-sliders-h"></i></div>
            </div>
        </div>
    </section>
</main>

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
        'use strict';

        (function($) {
            $('.subscribe-form').on('submit', function(e) {
                e.preventDefault();

                var email = $('#subscriber').val();
                if (!email) {
                    notify('error', `@lang('The email field is required')`);
                    return false;
                }

                var csrf = '{{ csrf_token() }}'
                var url = "{{ route('subscriber.store') }}";
                var data = {
                    email: email,
                    _token: csrf
                };

                $.post(url, data, function(response) {
                    if (response.success) {
                        notify('success', response.success);
                        $('#subscriber').val('');
                    } else {
                        notify('error', response.error);
                    }
                });
            });

            var swiper = new Swiper('.product-slider', {
                slidesPerView: 3,
                spaceBetween: 30,
                loop: false,
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
                        slidesPerView: 2,
                    },
                    575: {
                        slidesPerView: 1,
                    },
                }
            });

            var swiper = new Swiper('.widget-slider', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: true,
                autoplay: {
                    speed: 1000,
                    delay: 3000,
                },
                speed: 1000,
                breakpoints: {
                    991: {
                        slidesPerView: 1,
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
