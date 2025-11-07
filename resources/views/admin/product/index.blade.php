@extends('admin.layouts.app')

@section('panel')
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive--lg table-responsive">
                <table class="table--light style--two table">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Category')</th>
                            <th>@lang('Old Price')</th>
                            <th>@lang('New Price')</th>
                            <th>@lang('Total Sell')</th>
                            <th>@lang('Downloadable File')</th>
                            <th>@lang('Featured')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $item)
                            <tr>
                                <td>{{ strLimit(__($item->name), 20) }}</td>
                                <td>{{ strLimit(__($item->category->name), 20) }}</td>
                                <td>{{ showAmount($item->old_price) }}</td>
                                <td>{{ showAmount($item->new_price) }}</td>
                                <td>{{ $item->total_sell }}</td>
                                <td>
                                    @php echo $item->fileBadge @endphp
                                </td>
                                <td>
                                    @php echo $item->featureBadge @endphp
                                </td>

                                <td>
                                    <div class="button--group">
                                        <a class="btn btn-outline--primary btn-sm" href="{{ route('admin.product.update', $item->id) }}">
                                            <i class="las la-pen"></i>@lang('Edit')
                                        </a>

                                        @if ($item->featured == Status::ENABLE)
                                            <button class="btn btn-outline--warning btn-sm confirmationBtn" data-question="@lang('Are you sure to unfeatured this product?')" data-action="{{ route('admin.product.feature', $item->id) }}">
                                                <i class="las la-star"></i>@lang('Unfeatured')
                                            </button>
                                        @else
                                            <button class="btn btn-outline--success confirmationBtn btn-sm" data-question="@lang('Are you sure to featured this product?')" data-action="{{ route('admin.product.feature', $item->id) }}">
                                                <i class="las la-star"></i>@lang('Featured')
                                            </button>
                                        @endif

                                        @if ($item->status == Status::ENABLE)
                                            <button class="btn btn-outline--danger btn-sm confirmationBtn" data-question="@lang('Are you sure to disable this product?')" data-action="{{ route('admin.product.status', $item->id) }}">
                                                <i class="las la-eye-slash"></i>@lang('Disable')
                                            </button>
                                        @else
                                            <button class="btn btn-outline--success confirmationBtn btn-sm" data-question="@lang('Are you sure to enable this product?')" data-action="{{ route('admin.product.status', $item->id) }}">
                                                <i class="las la-eye"></i>@lang('Enable')
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($products->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($products) }}
            </div>
        @endif
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />
    <a class="btn btn-outline--primary h-45" data-modal_title="@lang('Add New Product')" href="{{ route('admin.product.create') }}">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush
