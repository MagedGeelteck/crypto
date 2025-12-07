@extends('admin.layouts.app')

@push('style')
<style>
    .gap-2 {
        gap: 0.5rem !important;
    }
    .badge {
        padding: 5px 10px;
        border-radius: 4px;
    }
</style>
@endpush

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

    @if ($deposit)
        <div class="row mb-none-30 mt-30">
            <div class="col-xl-6 col-md-6 mb-30">
                <div class="card b-radius--10 box--shadow1 overflow-hidden">
                    <div class="card-body">
                        <h5 class="text-muted mb-20">@lang('Payment Information')</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Order Code')
                                <span class="font-weight-bold">{{ $deposit->code ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Transaction ID')
                                <span class="font-weight-bold">{{ $deposit->trx ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Payment Method')
                                <span class="font-weight-bold">{{ $deposit->gateway ? $deposit->gateway->name : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Amount')
                                <span class="font-weight-bold">{{ showAmount($deposit->amount ?? 0) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Final Amount')
                                <span class="font-weight-bold">{{ showAmount($deposit->final_amount ?? 0) }} {{ $deposit->method_currency ?? '' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Status')
                                <span>
                                    @php
                                        $status = $deposit->status ?? null;
                                    @endphp
                                    @if ($status == Status::PAYMENT_SUCCESS)
                                        <span class="badge badge--success">@lang('Approved')</span>
                                    @elseif($status == Status::PAYMENT_PENDING)
                                        <span class="badge badge--warning">@lang('Pending')</span>
                                    @elseif($status == Status::PAYMENT_REJECT)
                                        <span class="badge badge--danger">@lang('Rejected')</span>
                                    @else
                                        <span class="badge badge--dark">@lang('Initiated')</span>
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6 col-md-6 mb-30">
                <div class="card b-radius--10 box--shadow1 overflow-hidden">
                    <div class="card-body">
                        <h5 class="text-muted mb-20">@lang('Shipping Address')</h5>
                        <ul class="list-group">
                            @php
                                $shipping = $deposit->shipping ?? [];
                                if (!is_array($shipping)) {
                                    $shipping = [];
                                }
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Name')
                                <span class="font-weight-bold">{{ $shipping['name'] ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Contact No')
                                <span class="font-weight-bold">{{ $shipping['mobile'] ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('PayPal Email')
                                <span class="font-weight-bold">{{ $shipping['email'] ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Address')
                                <span class="font-weight-bold">{{ $shipping['address'] ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deposit && $deposit->detail)
        <div class="row mb-none-30 mt-30">
            <div class="col-xl-12 col-md-12 mb-30">
                <div class="card b-radius--10 box--shadow1 overflow-hidden">
                    <div class="card-body">
                        <h5 class="text-muted mb-20">@lang('Payment Details Submitted by User')</h5>
                        <ul class="list-group">
                            @php
                                $details = is_object($deposit->detail) ? get_object_vars($deposit->detail) : (is_array($deposit->detail) ? $deposit->detail : []);
                            @endphp
                            @foreach ($details as $key => $value)
                                @php
                                    $displayValue = is_string($value) || is_numeric($value) ? $value : json_encode($value);
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang(str_replace('_', ' ', ucfirst($key)))
                                    <span class="font-weight-bold">{{ $displayValue }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deposit && $deposit->status == Status::PAYMENT_PENDING)
        <div class="row mt-30">
            <div class="col-md-12">
                <div class="card b-radius--10">
                    <div class="card-body">
                        <h5 class="card-title mb-3">@lang('Payment Actions')</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn--success approveBtn" data-id="{{ $deposit->id }}">
                                <i class="las la-check-circle"></i> @lang('Approve Payment')
                            </button>
                            <button type="button" class="btn btn--danger rejectBtn" data-id="{{ $deposit->id }}">
                                <i class="las la-times-circle"></i> @lang('Reject Payment')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($orderDetails->first() && $orderDetails->first()->status == Status::PROCESSING)
        <div class="row mt-30">
            <div class="col-md-12">
                <button type="button" class="btn btn--primary h-45 w-100 completeModalBtn" data-code="{{ $orderDetails->first()->code }}">
                    <i class="las la-check-circle"></i> @lang('Mark as Completed')
                </button>
            </div>
        </div>
    @endif

    {{-- Approve Modal --}}
    <div class="modal fade" id="approveModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Approve Payment Confirmation')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.deposit.approve', '') }}" method="POST">
                    @csrf
                    <input name="id" type="hidden">
                    <div class="modal-body">
                        <p>@lang('Are you sure you want to approve this payment?')</p>
                        <p class="text-muted">@lang('This will mark the order as processing and notify the customer.')</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--dark" data-bs-dismiss="modal" type="button">@lang('Cancel')</button>
                        <button class="btn btn--success" type="submit">@lang('Approve Payment')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject Payment Confirmation')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.deposit.reject') }}" method="POST">
                    @csrf
                    <input name="id" type="hidden">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Reason for Rejection')</label>
                            <textarea name="message" class="form-control" rows="4" required>{{ old('message') }}</textarea>
                        </div>
                        <p class="text-muted">@lang('The customer will be notified about this rejection.')</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--dark" data-bs-dismiss="modal" type="button">@lang('Cancel')</button>
                        <button class="btn btn--danger" type="submit">@lang('Reject Payment')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Complete Modal --}}

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

            $('.approveBtn').on('click', function() {
                let modal = $("#approveModal");
                let id = $(this).data('id');
                let action = "{{ route('admin.deposit.approve', '') }}/" + id;
                modal.find('form').attr('action', action);
                modal.find('input[name=id]').val(id);
                modal.modal("show");
            });

            $('.rejectBtn').on('click', function() {
                let modal = $("#rejectModal");
                let id = $(this).data('id');
                modal.find('input[name=id]').val(id);
                modal.modal("show");
            });

        })(jQuery);
    </script>
@endpush
