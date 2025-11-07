@extends('admin.layouts.app')

@section('panel')
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive--lg table-responsive">
                <table class="table--light style--two table">
                    <thead>
                        <tr>
                            <th>@lang('Image')</th>
                            <th>@lang('Ad Size')</th>
                            <th>@lang('Url')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($advertisements as $ads)
                            <tr>
                                <td>
                                    <div class="user">
                                        <div class="thumb">
                                            <img class="plugin_bg" src="{{ getImage(getFilePath('ads') . '/' . @$ads->image) }}" alt="ads">
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $ads->resolution ?? 'N/A' }}</td>
                                <td>{{ $ads->redirect_url }}</td>
                                <td> @php echo $ads->statusBadge @endphp</td>
                                <td>
                                    @php
                                        $ads->image_with_path = getImage(getFilePath('ads') . '/' . $ads->image, null);
                                    @endphp
                                    <div class="button-group">
                                        <a class="btn btn-outline--dark btn-sm" data-rel="lightcase" href="{{ getImage(getFilePath('ads') . '/' . $ads->image) }}"> <i class="las la-eye"></i>@lang('view')</a>
                                        <button class="btn btn-outline--primary cuModalBtn btn-sm" data-modal_title="@lang('Update Category')" data-resource="{{ $ads }}">
                                            <i class="las la-pen"></i>@lang('Edit')
                                        </button>

                                        @if ($ads->status == Status::ENABLE)
                                            <button class="btn btn-outline--danger btn-sm confirmationBtn" data-question="@lang('Are you sure to disable this advertisement?')" data-action="{{ route('admin.advertise.status', $ads->id) }}">
                                                <i class="las la-eye-slash"></i>@lang('Disable')
                                            </button>
                                        @else
                                            <button class="btn btn-outline--success confirmationBtn btn-sm" data-question="@lang('Are you sure to enable this advertisement?')" data-action="{{ route('admin.advertise.status', $ads->id) }}">
                                                <i class="las la-eye"></i>@lang('Enable')
                                            </button>
                                        @endif
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ $emptyMessage }}</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        @if ($advertisements->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($advertisements) }}
            </div>
        @endif
    </div>

    <div class="modal fade" id="cuModal" role="dialog" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.advertise.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Add New Promo Banner')</h5>
                        <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Select Size')</label>
                            <select class="form-control select2" name="resolution" data-minimum-results-for-search="-1" required>
                                <option value="265x135">@lang('265x135')</option>
                                <option value="580x240">@lang('580x240')</option>
                                <option value="265x330">@lang('265x330')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Redirect Url')</label>
                            <input class="form-control" name="redirect_url" type="text" value="{{ old('redirect_url') }}" placeholder="@lang('https://example.com')" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Ad Image')</label>
                            <x-image-uploader class="w-100" type="ads" :required=true accept='.png, .jpg, .jpeg .gif' />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    
    <x-confirmation-modal />

@endsection

@push('breadcrumb-plugins')
    <x-search-form />
    <button class="btn btn-outline--primary h-45 cuModalBtn addBtn" data-modal_title="@lang('Add New Promo Banner')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/lightcase.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/lightcase.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            $('a[data-rel^=lightcase]').lightcase();

            $('.addBtn').on('click', function() {
                var imageSize = `{{ getImage(getFilePath('ads'), null) }}`
                console.log(imageSize);
                $('.image-upload-preview').css('background-image', 'url(' + imageSize + ')');
            });

        })(jQuery);
    </script>
@endpush
