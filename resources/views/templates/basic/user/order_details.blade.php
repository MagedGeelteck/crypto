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
                                        <th>@lang('Product Name')</th>
                                        <th>@lang('Quantity')</th>
                                        <th>@lang('Unit Price')</th>
                                        <th>@lang('Total Price')</th>
                                        <th>@lang('Review')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $key => $item)
                                        <tr>
                                            <td>{{ showDateTime($item->created_at, 'Y-m-d') }}</td>
                                            <td>{{ __($item->product->name) }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{ showAmount($item->product_price) }}</td>
                                            <td>{{ showAmount($item->total_price) }}</td>

                                            @if ($item->status == 1)
                                                @if (!auth()->user()->existedRating($item->product->id))
                                                    <td><button class="bg--base reviewBtn rounded px-1 text-white" data-id="{{ $item->product->id }}">@lang('Review')</button>
                                                    </td>
                                                @else
                                                    @php
                                                        $rating = getRatingByUserProduct(auth()->user()->id, $item->product->id);
                                                    @endphp
                                                    <td>
                                                        <span class="ratings">
                                                            @for ($i = 0; $i < $rating; $i++)
                                                                <i class="las la-star"></i>
                                                            @endfor
                                                            @for ($i = 0; $i < 5 - $rating; $i++)
                                                                <i class="lar la-star"></i>
                                                            @endfor
                                                        </span>
                                                    </td>
                                                @endif
                                            @else
                                                <td>@lang('Not available')</td>
                                            @endif

                                            <td>
                                                @if ($item->status == 1 && $item->product->product_file)
                                                    <p class="badge badge--success text-white"><a class="fas fa-download" href="{{ route('user.product.download', Crypt::encrypt($item->product->id)) }}"></a></p>
                                                @else
                                                @lang('N/A')
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">@lang('No data found')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($orders->hasPages())
                    <div class="paginate-warper">
                        {{ paginateLinks($orders) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if (count($orders) > 0)
        <div class="modal fade" id="reviewModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content py-4">
                    <div class="modal-header">
                        <h4 class="text-white">@lang('Give Your Rating')</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>

                    <form action="{{ route('user.rating') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="product-single-container">
                                <div class="row mb-30-none">
                                    <div class="col-xl-12 col-md-12 mb-30">

                                        <div class='starrr mb-2' id='star{{ $key }}'></div>
                                        <input id='star2_input' name='rating' type='hidden' value='0' required>
                                        <input name="product_id" type="hidden" value="" required>

                                        <div class="form-group">
                                            <label>@lang('Write your opinion')</label>
                                            <textarea class="form--control" name="review" rows="5" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn--base" type="submit">@lang('Submit')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script src="{{ asset($activeTemplateTrue . 'js/starrr.js') }}"></script>

    <script>
        'use strict';

        $('.reviewBtn').on('click', function() {
            var modal = $('#reviewModal');
            modal.find('input[name=product_id]').val($(this).data('id'));

            var $s2input = $('input[name=rating]');
            
            var indx = @php echo $orders->count() @endphp;

            var i = 0;

            for (i; i < indx; i++) {
                $(`#star${i}`).starrr({
                    max: 5,
                    rating: $s2input.val(),
                    change: function(e, value){
                        $s2input.val(value).trigger('input');
                    }
                });
            }
            
            modal.modal('show');
        });
    </script>
@endpush
