@foreach ($orders as $item)
    <div class="product remove-data">
        <div class="product-details">
            <h4 class="product-title">
                <a href="javascript:void(0)">{{__($item->product->name)}}</a>
            </h4>
            <span class="cart-product-info">
                <span class="cart-product-qty">{{$item->qty}}</span>
                x {{showAmount($item->product->new_price)}}
            </span>
        </div>
        <figure class="product-image-container">
            <a href="javascript:void(0)" class="product-image">
                <img src="{{ getImage(getFilePath('product') . '/' . $item->product->images->first()?->name), getFileSize('product') }}" alt="product" width="80" height="80">
            </a>
            <a href="javascript:void(0)" class="btn-remove remove-cart" data-id="{{Crypt::encrypt($item->id)}}" title="@lang('Remove Product')"><i class="las la-times"></i>
            </a>
        </figure>
    </div>
@endforeach
