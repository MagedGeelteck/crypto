@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="container">
        <div class="row mb-30-none pb-60">
            <div class="col-xl-9 mb-30">
                <div class="product-single-container">
                    <div class="row mb-30-none">
                        <div class="col-xl-5 col-md-6 mb-30">
                            <div class="xzoom-container">
                                <img class="xzoom5" id="xzoom-magnific" src="{{ getImage(getFilePath('product') . '/' . @$product->images[0]->name), getFileSize('product') }}" xoriginal="{{ getImage(getFilePath('product') . '/' . @$product->images[0]->name), getFileSize('product') }}" />
                                <div class="xzoom-thumbs mt-10">
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
                                <div class="ratings-container d-flex align-items-center flex-wrap">
                                    <div class="product-ratings">
                                        <span class="ratings">
                                            @for ($i = 0; $i < $product->avg_rating; $i++)
                                                <i class="las la-star"></i>
                                            @endfor
                                            @for ($i = 0; $i < 5 - $product->avg_rating; $i++)
                                                <i class="lar la-star"></i>
                                            @endfor
                                        </span>
                                    </div>
                                    <a class="rating-link" href="javascript:void(0)">( {{ count($product->ratings) }} @lang('Reviews') )</a>
                                </div>
                                <hr class="short-divider">
                                <div class="price-box">
                                    <span class="product-price">{{ showAmount($product->new_price) }}</span>
                                </div>

                                <div class="product-desc mb-2">
                                    <p>
                                        {{ __($product->short_description) }}
                                    </p>
                                </div>
                                <form>
                                    <div class="product-action d-flex align-items-center flex-wrap">
                                        <div class="product-quantity">
                                            <div class="product-plus-minus">
                                                <div class="dec qtybutton">-</div>
                                                <input class="product-plus-minus-box integer-validation" id="product-qty" name="qty" type="text" value="1">
                                                <div class="inc qtybutton">+</div>
                                            </div>
                                        </div>
                                        <button class="btn--base add-cart icon-shopping-cart productAddtocart" type="button" title="Add to Cart">@lang('Add to Cart')</button>
                                    </div>
                                </form>
                                <div class="product-single-share d-flex align-items-center flex-wrap">
                                    <label class="sr-only">@lang('Share'):</label>
                                    <div class="social-icons mr-2">

                                        <a class="social-icon social-facebook" href="http://www.facebook.com/sharer.php?u={{ urlencode(url()->current()) }}&p[title]={{ slug($product->name) }}" title="@lang('Facebook')" target="_blank"><i class="lab la-facebook-f"></i></a>
                                        <a class="social-icon social-twitter" href="http://twitter.com/share?text={{ slug($product->name) }}&url={{ urlencode(url()->current()) }}" title="@lang('Twitter')" target="_blank"><i class="lab la-twitter"></i></a>
                                        <a class="social-icon social-linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ slug($product->name) }}" title="@lang('Linkedin')" target="_blank"><i class="lab la-linkedin-in"></i></a>
                                        <a class="social-icon social-gplus" href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ slug($product->name) }}" title="@lang('Pinterest') +" target="_blank"><i class="lab la-pinterest-p"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-single-tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="product-tab-desc" data-bs-toggle="tab" href="#product-desc-content" role="tab" aria-controls="product-desc-content" aria-selected="true">@lang('Description')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="product-tab-reviews" data-bs-toggle="tab" href="#product-reviews-content" role="tab" aria-controls="product-reviews-content" aria-selected="false">@lang('Reviews') ({{ count($product->ratings) }})</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="product-desc-content" role="tabpanel" aria-labelledby="product-tab-desc">
                            <div class="product-desc-content">
                                @php echo $product->description @endphp
                            </div>
                        </div>
                        <div class="tab-pane fade" id="product-reviews-content" role="tabpanel" aria-labelledby="product-tab-reviews">
                            <div class="product-reviews-content">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <h3 class="reviews-title">{{ count($product->ratings) }} @lang('reviews for') {{ __($product->name) }}</h3>
                                        <ul class="comment-list">
                                            @foreach ($ratings->take(5) as $item)
                                                <li class="comment-container d-flex flex-wrap">
                                                    <div class="comment-avatar">
                                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $item->user->image, getFileSize('userProfile')) }}" alt="avatar">
                                                    </div>
                                                    <div class="comment-box">
                                                        <div class="ratings-container">
                                                            <div class="product-ratings">
                                                                @for ($i = 0; $i < $item->rating; $i++)
                                                                    <i class="las la-star"></i>
                                                                @endfor
                                                                @for ($i = 0; $i < 5 - $item->rating; $i++)
                                                                    <i class="lar la-star"></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="comment-info mb-1">
                                                            <h4 class="avatar-name">{{ $item->user->fullname }}</h4> - <span class="comment-date">{{ showDateTime($item->created_at, 'F d,Y') }}</span>
                                                        </div>
                                                        <div class="comment-text">
                                                            <p>{{ __($item->review) }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @if (count($ratings) > 5)
                                            <div class="text-center">
                                                <button class="btn--base add-cart icon-shopping-cart mt-4" id="loadMoreBtn" type="button">@lang('Load More')</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
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

@push('script')
    <script>
        'use strict';


        $(".xzoom").xzoom({
            tint: '#333',
            Xoffset: 15
        });

        var swiper = new Swiper('.product-single-slider', {
            slidesPerView: 4,
            spaceBetween: 10,
            loop: false,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            speed: 1000,
            breakpoints: {
                991: {
                    slidesPerView: 4,
                },
                767: {
                    slidesPerView: 4,
                },
                575: {
                    slidesPerView: 3,
                },
            }
        });



        var swiper = new Swiper('.product-slider-two', {
            slidesPerView: 4,
            spaceBetween: 30,
            loop: false,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
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


        (function($) {

            var counter = 5;

            $('#loadMoreBtn').on('click', function() {
                $.ajax({
                    type: "get",
                    url: "{{ route('load.more.rating') }}",
                    data: {
                        count: counter,
                        id: '{{ $product->id }}'
                    },
                    dataType: "json",
                    success: function(response) {

                        if (response.ratings.length < 5) {
                            $('#loadMoreBtn').remove();
                        }

                        if (response.html) {
                            $('.comment-list').append(response.html);
                        }

                        counter = parseInt(counter) + parseInt(5);
                    }
                });
            });


        })(jQuery);

        $('.productAddtocart').on('click', function() {

            var id = '{{ $product->id }}';
            var pQty = $('[name=qty]').val();
            var csrf = '{{ csrf_token() }}'

            $.ajax({
                type: "post",
                url: "{{ route('add.to.cart') }}",
                data: {
                    product_id: id,
                    qty: pQty,
                    _token: csrf
                },
                dataType: "json",


                success: function(response) {
                    if (response.success) {

                        notify('success', response.success);
                        $(document).find('.total-cart').text(response.cartCount);
                        $(document).find('.total-price').text(response.totalPrice);
                        $('.dropdown-cart-products').html('');
                        $('.dropdown-cart-products').html(response.html);

                        if (response.cartCount > 0) {

                            var checkoutHtml = `<div class="dropdown-cart-action">
                                                <a href="{{ route('cart') }}" class="btn--base w-100">@lang('Checkout')</a>
                                            </div>`;
                            $('.checkout-btn').html(checkoutHtml);
                        }

                    } else {
                        notify('error', response.error);
                    }
                }
            });
        });
    </script>
@endpush
