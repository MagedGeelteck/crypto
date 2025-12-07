@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $offerElements = getContent('home_page_offer.element');
    @endphp
    <section class="pb-60">
        <div class="container">
            <div class="sidebar-overlay"></div>
            <div class="sidebar-toggle"><i class="fas fa-sliders-h"></i></div>
            @push('script-lib')
                <script>
                    window.SLIDER_HOME_PRODUCTS = true;
                    window.SLIDER_HOME_WIDGET = true;
                    window.SUBSCRIBE_ENDPOINT = { url: "{{ route('subscriber.store') }}", csrf: "{{ csrf_token() }}" };
                </script>
            @endpush
                            @forelse($products as $item)
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-30">
                                    @include('Template::partials.product', ['item' => $item])
                                </div>
                            @empty
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-30">
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
