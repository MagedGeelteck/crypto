@extends('admin.layouts.app')

@section('panel')
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive--lg table-responsive">
                <table class="table--light style--two table">
                    <thead>
                        <tr>
                            <th>@lang('User')</th>
                            <th>@lang('Order Code')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Payment Status')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ @$item->user->fullname }}</span>
                                    <br>
                                    <span class="small">
                                        <a href="{{ route('admin.users.detail', $item->user->id) }}"><span>@</span>{{ $item->user->username }}</a>
                                    </span>
                                </td>
                                <td>
                                    {{ $item->code }}
                                </td>

                                <td>
                                    <span class="font-weight-bold">{{ showAmount($item->sum('total_price')) }}</span><br>
                                </td>

                                <td>
                                    <div>
                                        {{ showDateTime($item->created_at) }} <br>
                                        {{ diffForHumans($item->created_at) }}
                                    </div>
                                </td>

                                <td>
                                    @php echo $item->paymentStatusBadge @endphp
                                </td>

                                <td>
                                    @php echo $item->sellStatusBadge @endphp
                                </td>

                                <td>
                                    <div class="button--group">
                                        @if ($item->status == Status::PROCESSING)
                                            <button type="button" class="btn btn-outline--success btn-sm completeModalBtn" data-code={{ $item->code }}><i class="las la-check-circle"></i>@lang('Complete')</button>
                                        @endif

                                        <a class="btn btn-outline--primary btn-sm" href="{{ route('admin.order.details', $item->code) }}"><i class="las la-desktop"></i>@lang('Details')</a>
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

        @if ($orders->hasPages())
            <div class="card-footer py-4">
                @php echo paginateLinks($orders) @endphp
            </div>
        @endif
    </div>

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

    <div class="modal fade" id="messageModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Rejection Message')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--primary" type="submit">@lang('Ok')</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form dateSearch='yes' placeholder='Code / Product name' />
@endpush

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

            $('.rejectBtn').on('click', function() {
                let messageModal = $("#messageModal");
                let message = $(this).data('rejected_reason');
                messageModal.find('.message').text(message);
                messageModal.modal('show');
            })

        })(jQuery);
    </script>
@endpush
