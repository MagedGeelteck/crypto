@extends('admin.layouts.app')

@section('panel')
    <div class="card b-radius--10">
        <div class="card-body p-0">
            <div class="table-responsive--sm table-responsive">
                <table class="table--light style--two table">
                    <thead>
                        <tr>
                            <th>@lang('User')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Product Name')</th>
                            <th>@lang('Quantity')</th>
                            <th>@lang('Unit Price')</th>
                            <th>@lang('Total Price')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderDetails as $item)
                            <tr>

                                <td>
                                    <span class="font-weight-bold">{{ __($item->user->fullname) }}</span>
                                    <br>
                                    <span class="small">
                                        <a href="{{ route('admin.users.detail', $item->user_id) }}"><span>@</span>{{ __($item->user->username) }}</a>
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        {{ showDateTime($item->created_at) }} <br>
                                        {{ diffForHumans($item->created_at) }}
                                    </div>
                                </td>
                                <td>
                                    {{ __($item->product->name) }}
                                </td>
                                <td>
                                    {{ $item->qty }}
                                </td>
                                <td>
                                    {{ showAmount($item->product_price) }}
                                </td>
                                <td>
                                    {{ showAmount($item->total_price) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    @if (@$shippingAddress[0] != null)
        <div class="row mb-none-30 mt-30">
            <div class="col-xl-12 col-md-12 mb-30">
                <div class="card b-radius--10 box--shadow1 overflow-hidden">
                    <div class="card-body">
                        <h5 class="text-muted mb-20">@lang('Shipping Address')</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Name')
                                <span class="font-weight-bold">{{ @$shippingAddress[0]->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Contact No')
                                <span class="font-weight-bold">{{ @$shippingAddress[0]->mobile }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Email')
                                <span class="font-weight-bold">{{ @$shippingAddress[0]->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Address')
                                <span class="font-weight-bold">{{ @$shippingAddress[0]->address }}</span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($item->status == Status::PROCESSING)
        <button type="button" class="btn mt-3 btn--primary h-45 w-100 completeModalBtn" data-code={{ $item->code }}><i class="las la-check-circle"></i>@lang('Complete')</button>
    @endif

    <div class="modal fade" id="completeModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Make Order Completed Confirmation')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.order.complete') }}" method="POST">
                    @csrf
                    <input name="code" type="hidden" value="{{ @$item->code }}">
                    <div class="modal-body">
                        <p>@lang('Are you sure to make this order as completed?')</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--dark" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                        <button class="btn btn--primary" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.completeModalBtn').on('click', function() {
                let modal = $("#completeModal");
                let code = $(this).data('code');
                $('input[name=code]').val(code);
                modal.modal("show");
            });

        })(jQuery);
    </script>
@endpush
