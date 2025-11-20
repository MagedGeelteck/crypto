@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="container">
        <div class="row mb-30-none pb-60">
            <div class="col-xl-9 mb-30">
                <div class="product-single-container">
                    <div class="row mb-30-none">
                        <div class="col-xl-5 col-md-6 mb-30">
                            <div class="xzoom-container" >
                                <img class="" id="" src="{{ getImage(getFilePath('product') . '/' . @$product->images[0]->name), getFileSize('product') }}" xoriginal="{{ getImage(getFilePath('product') . '/' . @$product->images[0]->name), getFileSize('product') }}" />
                                <div class="xzoom-thumbs mt-10" hidden>
                                    <div class="product-single-slider">
                                        <div class="swiper-wrapper">
                                            @foreach ($product->images as $key => $item)
                                                <div class="swiper-slide">
                                                    <a href="{{ getImage(getFilePath('product') . '/' . @$product->images[$key]->name), getFileSize('product') }}"><img class="xzoom-gallery5" src="{{ getImage(getFilePath('product') . '/' . @$product->images[$key]->name), getFileSize('product') }}" title="The description goes here" width="80" xpreview="{{ getImage(getFilePath('product') . '/' . @$product->images[$key]->name), getFileSize('product') }}"></a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7 col-md-6 mb-30">
                            <div class="product-details-content">
                                <h2 class="product-title text-white">{{ __($product->name) }}</h2>
                                {{-- ratings removed from frontend product details --}}
                                <hr class="short-divider">
                                <div class="price-box">
                                    <span class="product-price">{{ showAmount($product->new_price) }}</span>
                                </div>

                                <div class="product-desc mb-2">
                                    <p>
                                        {{ __($product->short_description) }}
                                    </p>
                                </div>
                                {{-- Show full product description (render raw HTML). Keeping reviews tab below as well. --}}
                                @php
                                    $fullDesc = trim(strip_tags($product->description ?? ''));
                                @endphp
                                @if($fullDesc === '')
                                    <div class="alert alert-secondary product-full-description mb-3">
                                        @lang('No additional description provided.')
                                    </div>
                                @else
                                    
                                @endif
                                @if($product->id == 4)
                                    <div class="product-action d-flex align-items-center flex-wrap">
                                        <a href="{{ route('contact') }}?subject={{ urlencode($product->name) }}" class="btn--base btn-support" title="@lang('Contact Support')">@lang('Contact Support')</a>
                                    </div>
                                @else
                                    <form>
                                        <div class="product-action d-flex align-items-center flex-wrap">
                                            <div class="product-quantity" hidden>
                                                <div class="product-plus-minus">
                                                    <div class="dec qtybutton">-</div>
                                                    <input class="product-plus-minus-box integer-validation" id="product-qty" name="qty" type="text" value="1">
                                                    <div class="inc qtybutton">+</div>
                                                </div>
                                            </div>
                                            <button class="btn--base add-cart icon-shopping-cart productAddtocart" type="button" title="Add to Cart">@lang('Add to Cart')</button>
                                        </div>
                                    </form>
                                @endif
                                {{-- Social sharing removed from product details to avoid external clearnet links when serving via onion or per project requirement. --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-single-tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="product-tab-desc" data-bs-toggle="tab" href="#product-desc-content" role="tab" aria-controls="product-desc-content" aria-selected="true">@lang('Description')</a>
                        </li>
                        {{-- reviews tab removed from frontend --}}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="product-desc-content" role="tabpanel" aria-labelledby="product-tab-desc">
                            <div class="product-desc-content">
                                @php echo $product->description @endphp
                            </div>
                        </div>
                        {{-- reviews content removed from frontend --}}
                    </div>
                </div>
            </div>
            <aside class="sidebar-home col-xl-3 mobile-sidebar mb-30">
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
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        @if ($relatedProducts->count())
            <section class="product-section pb-60">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-12">
                            <div class="section-header">
                                <h3 class="section-title">@lang('Related Products')</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="product-slider-two">
                                <div class="swiper-wrapper">
                                    @foreach ($relatedProducts as $item)
                                        <div class="swiper-slide">
                                            @include('Template::partials.product', ['item' => $item])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

    </div>

@endsection

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/xzoom.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/swiper.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/xzoom.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/setup.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/swiper.min.js') }}"></script>
@endpush

@push('script-lib')
    <script>
        window.SLIDER_PRODUCT_SINGLE = true;
        window.SLIDER_PRODUCT_RELATED = true;
        window.PRODUCT_DETAILS_HELPERS = {
            productId: '{{ $product->id }}',
            csrf: '{{ csrf_token() }}',
            addToCartUrl: "{{ route('add.to.cart') }}",
            // loadMoreUrl removed because reviews are not shown on frontend
            cartUrl: "{{ route('cart') }}"
        };
    </script>
@endpush
