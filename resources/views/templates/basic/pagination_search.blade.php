@foreach($products as $item)
    <div class="col-xl-4 col-md-6 mb-30">
        @include('Template::partials.product', ['item' => $item])
    </div>
@endforeach
