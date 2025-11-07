@extends('admin.layouts.app')
@section('panel')
    <div class="card">
        <form action="{{ route('admin.product.store', @$product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" value="{{ old('name', @$product->name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>@lang('Category')</label>
                            <select class="form-control select2" id="category" name="category_id" required>
                                <option value="">@lang('Select one')</option>
                                @foreach ($categories as $item)
                                    <option data-subcategory="{{ $item->subcategories }}" value="{{ $item->id }}" @selected(@$product->category_id == $item->id)>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>@lang('Subcategory')</label>
                            <select class="form-control select2" id="subcategory" name="sub_category_id" required></select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="d-flex flex-wrap gap-1 justify-content-start">
                                <label>@lang('Old Price')</label>
                                <code class="text--small">(@lang('max 8 digits fractional'))</code>
                            </div>
                            <div class="input-group">
                                <input class="form-control" name="old_price" type="number" value="{{ old('old_price', @$product->old_price) }}" step="any">
                                <span class="input-group-text">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="d-flex flex-wrap gap-1 justify-content-start">
                                <label>@lang('New Price')</label>
                                <code class="text--small">(@lang('max 8 digits fractional'))</code>
                            </div>
                            <div class="input-group">
                                <input class="form-control" name="new_price" type="number" value="{{ old('new_price', @$product->new_price) }}" step="any" required>
                                <span class="input-group-text">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('Short Description')</label>
                            <textarea class="form-control" id="" name="short_description" rows="5" required>{{ old('short_description', @$product->short_description) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('Description')</label>
                            <small><code>(@lang('HTML or plain text allowed'))</code></small>
                            <textarea class="form-control nicEdit" name="description" rows="10" placeholder="@lang('Enter your message')">{{ @$product->description }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="payment-method-body">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary d-flex align-items-center justify-content-between text-white">
                                    <div>
                                        @lang('Upload Downloadable File')

                                        @if (@$product->product_file)
                                            <small class="text--warning text-bold">(@lang('Previous file detected. By uploading new one previous one will be removed'))</small>
                                        @endif
                                    </div>
                                    @if (@$product->product_file)
                                        <div>
                                            <a class="btn btn--success btn-sm" data-bs-toggle="tooltip" data-bs-title="Download File" href="{{ getImage(getFilePath('productFile') . '/' . $product->product_file) }}" download><i class="las la-download me-0"></i></a>
                                        </div>
                                    @endif

                                </h5>
                                <div class="card-body">
                                    <label>@lang('Select file')</label>
                                    <input class="form-control" name="product_file" type="file" accept=".zip,.pdf,.txt">
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            @lang('Supported Files:')
                                            <b>@lang('.zip, pdf, txt')</b>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="payment-method-body">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary text-white">@lang('Product Images')</h5>
                                <div class="card-body">
                                    <div class="input-images"></div>
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            @lang('Supported Files:')
                                            <b>@lang('.png, .jpg, .jpeg')</b>
                                            @lang('& you can upload maximum ') <b>@lang('10')</b> @lang('images').
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.product.index') }}" />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/image-uploader.min.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/image-uploader.min.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script>
        "use strict";

        (function($) {
            $('#category').on('change', function() {

                var product = @json(@$product);
                var subcategory = $(this).find('option:selected').data('subcategory');

                $('#subcategory').empty();
                var options = `<option value="">@lang('Select One')</option>`;

                $.each(subcategory, function(index, value) {
                    if (product) {
                        var selected = (product.sub_category_id == value.id) ? 'selected' : '';
                    } else {
                        var selected = '';
                    }

                    options += `<option value="${value.id}" ${selected}>${value.name}</option>`;
                });
                $('#subcategory').html(options);
            }).change();


            // image uploder
            @if (isset($images))
                let preloaded = @json($images);
            @else
                let preloaded = [];
            @endif

            $('.input-images').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'image',
                preloadedInputName: 'old',
                maxSize: 3 * 1024 * 1024,
                maxFiles: 10,
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .payment-method-body .card-body {
            border: 1px solid #4634ff;
        }
    </style>
@endpush
