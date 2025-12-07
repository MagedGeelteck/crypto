@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pb-60 pt-60">
        <div class="container">
            {{-- Checkout Progress Tracker --}}
            <div class="checkout-progress-wrapper-formal mb-5">
                <div class="checkout-progress">
                    <div class="progress-step active completed">
                        <div class="step-icon">
                            <i class="las la-shopping-cart"></i>
                        </div>
                        <div class="step-label">Cart</div>
                        <div class="step-number">1</div>
                    </div>
                    <div class="progress-line active"></div>
                    <div class="progress-step active">
                        <div class="step-icon">
                            <i class="lab la-bitcoin"></i>
                        </div>
                        <div class="step-label">Payment</div>
                        <div class="step-number">2</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-icon">
                            <i class="las la-check-circle"></i>
                        </div>
                        <div class="step-label">Confirmation</div>
                        <div class="step-number">3</div>
                    </div>
                </div>
            </div>

            <div class="row mb-30-none">
                <div class="col-xl-12 mb-30">
                    <div class="checkout-cart-area">
                        <h3 class="checkout-section-title">
                            <i class="las la-shopping-bag"></i> @lang('Order Summary')
                        </h3>
                        <div class="checkout-products-list">
                            @foreach ($orders as $item)
                                <div class="checkout-product-item remove-data">
                                    <div class="product-image">
                                        <img src="{{ getImage(getFilePath('product') . '/' . @$item->product->images->first()?->name), getFileSize('product') }}" alt="product">
                                    </div>
                                    <div class="product-details">
                                        <h5 class="product-name">{{ __(@$item->product->name) }}</h5>
                                        <div class="product-meta">
                                            <span class="quantity-badge">
                                                <i class="las la-cube"></i> @lang('Qty'): {{ $item->qty }}
                                            </span>
                                            <span class="price-badge">
                                                <i class="las la-tag"></i> {{ showAmount(@$item->product->new_price) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="product-total">
                                        <div class="total-price">{{ showAmount($item->total_price) }}</div>
                                        <button class="remove-btn remove-cart" data-id="{{ Crypt::encrypt($item->id) }}" type="button" title="@lang('Remove')">
                                            <i class="las la-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="shipping-details-area mt-60">
                        @auth
                            {{-- Daily Transaction Limit Warning --}}
                            @if(isset($transactionsToday))
                                @if($transactionsToday >= 2)
                                    <div class="alert alert-danger mb-4" role="alert">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        <strong>@lang('Daily Limit Reached:')</strong> 
                                        @lang('You have reached your daily transaction limit of 2 transactions. Please try again tomorrow.')
                                    </div>
                                @elseif($transactionsToday == 1)
                                    <div class="alert alert-warning mb-4" role="alert">
                                        <i class="fas fa-info-circle"></i> 
                                        <strong>@lang('Daily Limit Notice:')</strong> 
                                        @lang('You have made 1 transaction today. You can make 1 more transaction before reaching your daily limit of 2 transactions.')
                                    </div>
                                @else
                                    <div class="alert alert-info mb-4" role="alert">
                                        <i class="fas fa-info-circle"></i> 
                                        <strong>@lang('Daily Limit:')</strong> 
                                        @lang('You can make up to 2 transactions per day. You have 2 transactions remaining today.')
                                    </div>
                                @endif
                            @endif

                            <form class="shipping-form" action="{{ route('user.deposit.insert') }}" method="POST">
                                @csrf
                                <div class="row justify-content-center mb-30-none">
                                    <div class="col-xl-12 col-lg-12 mb-30">
                                        <div class="cart-total-area">
                                            {{-- BTC Payment Details --}}
                                            <div class="btc-payment-area">
                                                <h4 class="title mb-20 text-white">@lang('BTC Payment Details')</h4>
                                                <div class="section--bg panel-card-body p-4">
                                                    <div class="form-group">
                                                        <label class="text-white mb-2">@lang('Total Amount')</label>
                                                        <input class="form--control" type="text" value="{{ showAmount($totalPrice) }}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-white mb-2">@lang('Network')</label>
                                                        <input class="form--control" type="text" value="BTC" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-white mb-2">@lang('BTC Address')</label>
                                                        <div class="input-group">
                                                            <input class="form--control btc-address" id="btcAddress" type="text" value="12EnY2zjRAszBsj2qoxDTwPLMBGStrTwcq" readonly>
                                                            <button class="input-group-text btn--base" id="copyBtcAddress" type="button" title="@lang('Copy Address')">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="form-group text-center mt-3">
                                                        <img src="{{ asset('assets/images/BTCAddress.JPG') }}" alt="BTC QR Code" class="img-fluid" style="max-width: 250px; border-radius: 8px; border: 2px solid #fff;">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <input name="currency" type="hidden" value="BTC">
                                            <input name="gateway" type="hidden" value="1000">
                                            <input name="amount" type="hidden" value="{{ $totalPrice }}">
                                            
                                            <div class="checkout-btn d-flex justify-content-end form-group mt-30">
                                                @if(isset($transactionsToday) && $transactionsToday >= 2)
                                                    <button class="submit-btn w-100" type="button" disabled style="opacity: 0.6; cursor: not-allowed;">
                                                        <i class="fas fa-lock"></i> @lang('Daily Limit Reached')
                                                    </button>
                                                @else
                                                    <button class="submit-btn w-100" type="submit">@lang('Proceed To Checkout')</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="row justify-content-between mb-30-none">
                                <div class="col-xl-4 col-lg-4 mb-30">
                                    <div class="cart-total-area">
                                        <div class="checkout-btn d-flex justify-content-end form-group">
                                            <a class="btn--base w-100 account-open-btn" href="{{ route('user.login') }}">@lang('Login To Checkout')</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 mb-30">
                                    <div class="cart-total-area">
                                        <div class="panel-table">
                                            <div class="panel-card-body section--bg table-responsive">
                                                <table class="custom-table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('Grand total')</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="total-price" data-label="@lang('Grand total')">{{ showAmount($totalPrice) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .btc-payment-area .input-group-text {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btc-payment-area .input-group-text:hover {
            opacity: 0.8;
        }
        .btc-address {
            font-family: monospace;
            font-size: 14px;
        }
        .alert-info {
            background-color: rgba(23, 162, 184, 0.1);
            border: 1px solid rgba(23, 162, 184, 0.3);
            color: #17a2b8;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";

        (function($) {
            // Copy BTC Address functionality
            $('#copyBtcAddress').on('click', function() {
                var btcAddress = document.getElementById('btcAddress');
                btcAddress.select();
                btcAddress.setSelectionRange(0, 99999); // For mobile devices
                
                // Copy to clipboard
                navigator.clipboard.writeText(btcAddress.value).then(function() {
                    // Show success feedback
                    var originalHtml = $('#copyBtcAddress').html();
                    $('#copyBtcAddress').html('<i class="fas fa-check"></i>');
                    
                    setTimeout(function() {
                        $('#copyBtcAddress').html(originalHtml);
                    }, 2000);
                }).catch(function(err) {
                    // Fallback for older browsers
                    document.execCommand('copy');
                    var originalHtml = $('#copyBtcAddress').html();
                    $('#copyBtcAddress').html('<i class="fas fa-check"></i>');
                    
                    setTimeout(function() {
                        $('#copyBtcAddress').html(originalHtml);
                    }, 2000);
                });
            });
        })(jQuery);
    </script>
@endpush
