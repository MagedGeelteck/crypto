@php
    $headerContent = getContent('header.content', true);
    $categories = App\Models\Category::with(['subcategories', 'products', 'products.subcategory'])
        ->latest()
        ->get();
@endphp

<div class="page-wrapper">

    <header class="header-section">
        <div class="header">
            <div class="header-top-area">
                <div class="container">
                    <div class="header-top-content-area d-flex align-items-center justify-content-center flex-wrap">

                        <div class="header-top-right d-flex align-items-center flex-wrap">

                            <div class="header-menu">
                                <ul>
                                    
                                    <li class="mobile-none"><a href="{{ route('home') }}"><i class="las la-home"></i><span>@lang('Home')</span></a></li>
                                    {{-- Products link removed as per request --}}
                                    {{-- Blog link removed as per request --}}

                                    @foreach ($pages as $k => $data)
                                        <li class="mobile-none"><a href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a></li>
                                    @endforeach

                                    <li class="mobile-none"><a href="{{ route('contact') }}"><i class="las la-envelope"></i><span>@lang('Support Ticket')</span></a></li>

                                    @auth
                                        <li><a class="pe-1" href="{{ route('user.home') }}"><i class="las la-tachometer-alt"></i><span>@lang('Dashboard')</span></a></li>
                                    @else
                                        <li><a class="pe-1" href="{{ route('user.login') }}"><i class="las la-sign-in-alt"></i><span>@lang('Login')</span></a></li>
                                    @endauth
                                </ul>
                            </div>
                            @if (gs('multi_language'))
                                <div class="header-lang-right">
                                    <div class="language-select-area">
                                        @php
                                            $language = App\Models\Language::all();
                                            $selectLang = $language->where('code', config('app.locale'))->first();
                                            $currentLang = session('lang') ? $language->where('code', session('lang'))->first() : $language->where('is_default', Status::YES)->first();
                                        @endphp
                                        <div class="custom--dropdown">
                                            <div class="custom--dropdown__selected dropdown-list__item">
                                                <div class="thumb"> <img src="{{ getImage(getFilePath('language') . '/' . $currentLang->image, getFileSize('language')) }}" alt="image"></div>
                                                <span class="text"> {{ __(@$selectLang->name) }} </span>
                                            </div>
                                            <ul class="dropdown-list">
                                                @foreach ($language as $item)
                                                    <li class="dropdown-list__item">
                                                        <a class="thumb" href="{{ route('lang', $item->code) }}"> <img src="{{ getImage(getFilePath('language') . '/' . $item->image, getFileSize('language')) }}" alt="image">
                                                            <span class="text"> {{ __($item->name) }} </span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-middle-area">
                <div class="container">
                    <div class="header-middle-content d-flex align-items-center justify-content-between flex-wrap">
                        <div class="header-middle-left d-flex align-items-center col-lg-2 me-3 w-auto flex-wrap pl-0">
                            <button class="mobile-menu-toggler" type="button">
                                <i class="las la-bars"></i>
                            </button>
                            <a class="logo" href="{{ route('home') }}">
                                <img src="{{ siteLogo() }}" alt="@lang('logo')">
                            </a>
                        </div>
                        <div class="header-right w-lg-max d-flex align-items-center ml-auto flex-wrap gap-2">
                            <div class="header-icon header-search header-search-inline header-search-category mr-lg-2 pr-lg-4 w-lg-max">
                                <a class="search-toggle" href="javascript:void(0)" role="button"><i class="las la-search"></i></a>
                                <form action="{{ route('products') }}" method="get">
                                    <div class="header-search-wrapper">
                                        <input class="form-control" id="q" name="search" type="search" value="{{ request()->search ?? null }}" placeholder="@lang('Search')...">
                                        <div class="select-custom body-text">
                                            <select id="cat" name="search_c">
                                                <option value="0">@lang('All')</option>
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item->id }}" @selected(request()->search_c == $item->id)>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button class="btn search-btn" type="submit"><i class="las la-search"></i></button>
                                    </div>
                                </form>
                            </div>

                            @if (!(request()->routeIs('cart') || request()->routeIs('user.deposit*')))
                                <div class="dropdown cart-dropdown">
                                    <a class="dropdown-toggle dropdown-arrow icon--style" data-toggle="dropdown" data-display="static" href="javascript:void(0)" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="las la-shopping-bag"></i>
                                        <span class="cart-count badge-circle">
                                            @php
                                                if (auth()->user()) {
                                                    $ordersCount = auth()->user()->myOrder()->with('product')->get();
                                                } else {
                                                    $ordersCount = App\Models\Order::where('order_number', session()->get('order_number'))->with('product')->get();
                                                }
                                            @endphp
                                            <span class="total-cart">{{ count($ordersCount) }}</span>
                                        </span>
                                    </a>
                                    <div class="dropdown-menu cart-wrapper">
                                        <div class="dropdownmenu-wrapper">
                                            <div class="dropdown-cart-header">
                                                <span class="total-cart">{{ count($ordersCount) }}</span> <span>@lang('Items')</span>
                                            </div>
                                            <div class="dropdown-cart-products">
                                                @foreach ($ordersCount as $item)
                                                    <div class="product remove-data">
                                                        <div class="product-details">
                                                            <h4 class="product-title">
                                                                <a href="javascript:void(0)">{{ __($item->product->name) }}</a>
                                                            </h4>
                                                            <span class="cart-product-info">
                                                                <span class="cart-product-qty">{{ $item->qty }}</span>
                                                                x {{ gs('cur_sym') }}{{ $item->product->new_price }}
                                                            </span>
                                                        </div>
                                                        <figure class="product-image-container">
                                                            <a class="product-image" href="javascript:void(0)">
                                                                <img src="{{ getImage(getFilePath('product') . '/' . $item->product->images->first()?->name), getFileSize('product') }}" alt="product">
                                                            </a>
                                                            <a class="btn-remove remove-cart" data-id="{{ Crypt::encrypt($item->id) }}" href="javascript:void(0)" title="@lang('Remove Product')"><i class="las la-times"></i>
                                                            </a>
                                                        </figure>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="dropdown-cart-total">
                                                <span>@lang('Total')</span>
                                                <span class="total-price float-right">{{ showAmount($ordersCount->sum('total_price'), currencyFormat: false) }}</span>
                                                <span>{{ gs('cur_text') }}</span>
                                            </div>
                                            <div class="checkout-btn">
                                                @if (count($ordersCount) > 0)
                                                    <div class="dropdown-cart-action">
                                                        <a class="btn--base w-100" href="{{ route('cart') }}">@lang('Go To Cart')</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="mobile-menu-overlay"></div>

    <div class="mobile-menu-container">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="las la-times"></i></span>
            <nav class="mobile-nav">
                <ul class="mobile-menu">
                    @if (gs('multi_language'))
                        <li class="mobile-language-menu">
                            <div class="language-select-area">
                                @php
                                    $language = App\Models\Language::all();
                                    $selectLang = $language->where('code', config('app.locale'))->first();
                                    $currentLang = session('lang') ? $language->where('code', session('lang'))->first() : $language->where('is_default', Status::YES)->first();
                                @endphp

                                <div class="custom--dropdown">
                                    <div class="custom--dropdown__selected dropdown-list__item">
                                        <div class="thumb"> <img src="{{ getImage(getFilePath('language') . '/' . $currentLang->image, getFileSize('language')) }}" alt="image"></div>
                                        <span class="text"> {{ __(@$selectLang->name) }} </span>
                                    </div>
                                    <ul class="dropdown-list">
                                        @foreach ($language as $item)
                                            <li class="dropdown-list__item">
                                                <a class="thumb" href="{{ route('lang', $item->code) }}"> <img src="{{ getImage(getFilePath('language') . '/' . $item->image, getFileSize('language')) }}" alt="image">
                                                    <span class="text"> {{ __($item->name) }} </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endif
                    <li class="active"><a href="{{ route('home') }}"><i class="las la-home"></i>@lang('Home')</a></li>
                    {{-- Products link removed from mobile menu --}}
                    {{-- Blog link removed from mobile menu --}}
                    @foreach ($pages as $k => $data)
                        <li><a href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a></li>
                    @endforeach
                    <li><a href="{{ route('contact') }}"><i class="las la-envelope"></i>@lang('Contact')</a></li>
                </ul>
            </nav>
        </div>
    </div>

    @push('script')
        <script>
            (function($) {
                "use strict";

                $(document).on('click', '.remove-cart', function() {

                    var id = $(this).data('id');
                    var csrf = '{{ csrf_token() }}'

                    var url = "{{ route('remove.cart') }}";
                    var data = {
                        id: id,
                        _token: csrf
                    };

                    var thisData = $(this);

                    $.post(url, data, function(response) {

                        if (response) {
                            $('.total-cart').text(response.cartCount);
                            $('.total-price').text(response.totalPrice);
                            thisData.closest('.remove-data').remove();

                            if (response.cartCount <= 0) {
                                location.reload(true);
                            }

                            if (response.shipable == 0) {
                                $('.shipable').html('');
                            }

                            notify('success', 'Product has been removed from cart successfully!');
                        } else {
                            notify('error', response.error);
                        }
                    });
                });


                $('.addtocart').on('click', function() {
                    var id = $('.product-id').val();
                    var pQty = $('.product-qty').val();
                    var csrf = '{{ csrf_token() }}';
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
                                // Redirect to cart page immediately after adding product
                                setTimeout(function() {
                                    window.location.href = "{{ route('cart') }}";
                                }, 500); // Small delay to show the success message
                            } else {
                                notify('error', response.error);
                            }
                        }
                    });
                });

            })(jQuery);
        </script>
    @endpush
