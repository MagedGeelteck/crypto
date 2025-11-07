@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="transaction-area mt-30">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-12 col-md-12 col-sm-12 mb-30">
                <div class="panel-table-area">
                    <div class="panel-table">
                        <div class="panel-card-body section--bg table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Code')</th>
                                        <th>@lang('Total Price')</th>
                                        <th>@lang('Payment Status')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $key => $item)
                                        <tr>
                                            <td>{{ showDateTime($item->created_at, 'Y-m-d') }}</td>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ showAmount($item->total_amount) }}</td>
                                            <td>
                                                @php echo $item->paymentStatusBadge @endphp
                                            </td>
                                            <td>
                                                @php echo $item->sellStatusBadge @endphp
                                            </td>
                                            <td>
                                                <a class="badge badge--success text-white" href="{{ route('user.order.details', $item->code) }}">@lang('Details')</a>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="justify-content-center no-data-table text-center" colspan="100%">@lang('No data found')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($products->hasPages())
                    <div class="paginate-warper">
                        {{ paginateLinks($products) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="messageModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content py-4">
                <div class="modal-header">
                    <h5 class="modal-title text-white">@lang('Rejection Message')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn--base" data-bs-dismiss="modal" type="button">@lang('Ok')</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

            $('.rejectBtn').on('click', function() {
                let messageModal = $("#messageModal");
                let message = $(this).data('rejected_reason');
                messageModal.find('.message').text(message);
                messageModal.modal('show');
            })
        })(jQuery);
    </script>
@endpush
