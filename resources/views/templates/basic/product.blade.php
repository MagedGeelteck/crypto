@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $offerElements = getContent('home_page_offer.element');
    @endphp
    <section class="pb-60">
        <div class="container">
            <div class="sidebar-overlay"></div>
            <div class="sidebar-toggle"><i class="fas fa-sliders-h"></i></div>
            <div class="row mb-30-none">
                <aside class="sidebar-home col-xl-3 mobile-sidebar mb-30">
                    <div class="aside-inner">
                        <div class="widget widget-range mb-30">
                            <h3 class="widget-range-title">@lang('Filter By Price')</h3>
                            <div class="widget-range-area">
                                <div id="slider-range"></div>
                                <div class="price-range">
                                    <label for="amount">@lang('Price') :</label>
                                    <input id="amount" type="text" readonly>
                                    <input name="min_price" type="hidden" value="{{ $min }}">
                                    <input name="max_price" type="hidden" value="{{ $max }}">
                                </div>
                            </div>
                        </div>
                        <div class="side-menu-wrapper mb-30">
                            <h3 class="side-menu-title">@lang('Categories')</h3>
                            <ul class="side-menu mx-3 mb-2 px-2 pt-2">
                                @forelse($categories as $item)
                                    <li class="has-sub">
                                        <a href="javascript:void(0)">{{ __($item->name) }}</a>

                                        @if (count($item->subcategories) > 0)
                                            <span class="side-menu-toggle"></span>
                                            <ul>
                                                @foreach ($item->subcategories->where('status', Status::ENABLE) as $data)
                                                    <li><a href="{{ route('subcategory.search', $data->id) }}">{{ __($data->name) }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @empty
                                    <li class="text-center text-white">@lang('No product found')</li>
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
                    </div>
                </aside>
                <div class="col-xl-9 mb-30">
                    <nav class="toolbox">
                        <div class="toolbox-left">
                            <div class="toolbox-item toolbox-sort">
                                <label>@lang('Sort By') :</label>
                                <div class="select-custom">
                                    <select class="form-control" name="sortby">
                                        <option value="0">@lang('Sort by Oldest')</option>
                                        <option value="1" selected>@lang('Sort by Newnest')</option>
                                        <option value="2">@lang('Sort by Price: Low to High')</option>
                                        <option value="3">@lang('Sort by Price: High to Low')</option>
                                        <option value="4">@lang('Sort by Rating')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </nav>
                    <div class="main-content">
                        <div class="row mb-30-none">
                            @forelse($products as $item)
                                <div class="col-xl-4 col-md-6 mb-30">
                                    @include('Template::partials.product', ['item' => $item])
                                </div>
                            @empty
                                <div class="col-xl-4 col-md-6 mb-30">
                                    <div class="product-default">
                                        <div class="product-details d-flex align-items-start flex-wrap">
                                            <h3 class="product-title">
                                                @lang('No data found')
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        @if ($products->hasPages())
                            <div class="paginate-warper mt-5">
                                {{ paginateLinks($products) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            var searchProduct = null;
            var searchCategory = null;

            @if (request('search'))
                searchProduct = "{{ request('search') }}";
            @endif

            @if (request('search_c') > 0)
                searchCategory = "{{ request('search_c') }}";
            @endif

            var sortBy = 1;
            let min = {{ $min }};
            let max = {{ $max }};

            $("#slider-range").slider({
                range: true,
                min: min,
                max: max,
                values: [min, max],

                slide: function(event, ui) {

                    $("#amount").val("{{ gs('cur_sym') }}" + ui.values[0] + " - $" + ui.values[1]);
                    $('input[name=min_price]').val(ui.values[0]);
                    $('input[name=max_price]').val(ui.values[1]);

                },
                change: function() {
                    var min = $('input[name="min_price"]').val();
                    var max = $('input[name="max_price"]').val();
                    counter = 9
                    getFilteredData(min, max, sortBy, searchProduct, searchCategory);
                }
            });

            $("#amount").val("{{ gs('cur_sym') }}" + min + " - {{ gs('cur_sym') }}" + max);

            $('select[name="sortby"]').on('change', function() {
                sortBy = $('select[name="sortby"]').find(":selected").val();

                min = $('input[name=min_price]').val();
                max = $('input[name=max_price]').val();
                counter = 9;
                getFilteredData(min, max, sortBy, searchProduct, searchCategory);
            });

            function getFilteredData(min, max, sortBy, searchProduct, searchCategory) {

                $.ajax({
                    type: "get",
                    url: "{{ route('product.filtered') }}",
                    data: {
                        "min": min,
                        "max": max,
                        "sortby": sortBy,
                        "search_p": searchProduct,
                        "search_c": searchCategory
                    },

                    dataType: "json",
                    success: function(response) {
                        if (response.html) {
                            $('.main-content').html(response.html);
                        } else {
                            $.each(response.html, function(i, val) {
                                notify('error', val);
                            });
                        }
                    }
                });
            }

            var counter = 9;
            var path = `{{ asset(getFilePath('product')) }}`;

            $(document).on('click', '#loadMoreBtn', function() {

                sortBy = $('select[name="sortby"]').find(":selected").val();

                min = $('input[name=min_price]').val();
                max = $('input[name=max_price]').val();

                $.ajax({
                    type: "get",
                    url: "{{ route('load.more.products') }}",
                    data: {
                        count: counter,
                        min: min,
                        max: max,
                        sortby: sortBy
                    },
                    dataType: "json",

                    success: function(response) {
                        if (response.productCount < 9) {
                            $('#loadMoreBtn').remove();
                        }

                        if (response.html) {
                            $('.product-list').append(response.html);
                        }

                        counter = parseInt(counter) + 9;
                    }
                });
            });

        })(jQuery)
    </script>
@endpush
