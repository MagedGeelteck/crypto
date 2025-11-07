<div class="row mb-30-none product-list">
    @forelse($products->take(9) as $item)
        <div class="col-xl-4 col-md-6 mb-30">
            @include('Template::partials.product', ['item' => $item])
        </div>
    @empty
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="product-default">
                <div class="product-details d-flex flex-wrap align-items-start">
                    <h3 class="product-title">
                        @lang('No data found')
                    </h3>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if (count($products) > 9)
    <div class="text-center mt-4">
        <button type="button" class="btn--base add-cart icon-shopping-cart mt-4" id="loadMoreBtn">@lang('Load More')</button>
    </div>
@endif
