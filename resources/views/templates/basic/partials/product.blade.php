<div class="product-default">
    <figure>
        <a href="{{ route('product.details', [$item->id, slug($item->name)]) }}">
            <img src="{{ getImage(getFilePath('product') . '/' . @$item->images->first()?->name), getFileSize('product') }}" alt="product">
        </a>
        @if ($item->featured == Status::YES)
            <div class="label-group">
                <span class="product-label label-sale">@lang('Featured')</span>
            </div>
        @endif

        <button class="btn-quickview quick-view" data-toggle="modal" data-resource="{{ $item }}" data-image="{{ getImage(getFilePath('product') . '/' . @$item->images->first()?->name), getFileSize('product') }}"> @lang('Quick View') </button>

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
        <div class="ratings-container">
            <div class="product-ratings">
                <span class="ratings">
                    @php echo displayRating($item->avg_rating) @endphp
                </span>
            </div>
        </div>
        <div class="price-box">
            @if ($item->old_price)
                <span class="old-price">{{ showAmount($item->old_price) }}</span>
            @endif
            <span class="product-price">{{ showAmount($item->new_price) }}</span>
        </div>
    </div>
</div>
