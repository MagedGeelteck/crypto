<div class="product-default">
    <figure>
        <a href="{{ route('product.details', [$item->id, slug($item->name)]) }}">
            <img src="{{ getImage(getFilePath('product') . '/' . @$item->images->first()?->name), getFileSize('product') }}" alt="{{ __($item->name) }}">
        </a>
        @if ($item->featured == Status::YES)
            <div class="label-group">
                <span class="product-label label-sale">@lang('Featured')</span>
            </div>
        @endif
    </figure>
    
    <div class="product-details">
        <div class="category-wrap">
            <div class="category-list">
                <a href="{{ route('subcategory.search', $item->subcategory->id) }}" class="product-category">
                    {{ __($item->subcategory->name) }}
                </a>
            </div>
        </div>
        
        <h3 class="product-title">
            <a href="{{ route('product.details', [$item->id, slug($item->name)]) }}">
                {{ __($item->name) }}
            </a>
        </h3>
        
        <div class="price-box">
            <span class="product-price">{{ showAmount($item->new_price) }}</span>
        </div>
    </div>
</div>
