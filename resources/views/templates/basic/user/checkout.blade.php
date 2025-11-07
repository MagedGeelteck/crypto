@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pb-60">
        <div class="container">
            <div class="row mb-30-none">
                <div class="col-xl-12 mb-30">
                    <div class="cart-area">
                        <div class="panel-table">
                            <div class="panel-card-body section--bg table-responsive">
                                <table class="custom-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Product Name')</th>
                                            <th>@lang('Quantity')</th>
                                            <th>@lang('Unit Price')</th>
                                            <th>@lang('Total Price')</th>
                                            <th>@lang('Remove')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $item)
                                            <tr class="remove-data">
                                                <td data-label="@lang('Product Name')">
                                                    <div class="p-item d-flex align-items-center flex-wrap">
                                                        <div class="p-thumb">
                                                            <img src="{{ getImage(getFilePath('product') . '/' . @$item->product->images->first()?->name), getFileSize('product') }}" alt="product">
                                                        </div>
                                                        <div class="p-content">
                                                            <h5 class="title text-white">{{ __(@$item->product->name) }}</h5>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td> {{ $item->qty }} </td>
                                                <td>{{ showAmount(@$item->product->new_price) }}</td>
                                                <td>{{ showAmount($item->total_price) }}</td>
                                                <td>
                                                    <a class="remove-product remove-cart text--danger" data-id="{{ Crypt::encrypt($item->id) }}" href="javascript:void(0)"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="shipping-details-area mt-60">
                        @auth
                            <form class="shipping-form" action="{{ route('user.deposit.insert') }}" method="POST">
                                @csrf
                                <div class="row justify-content-center mb-30-none">
                                    <div class="col-xl-8 col-lg-8 mb-30">
                                        <div class="shipping-wrapper shipable">
                                            @if ($shipable == 1)
                                                <h4 class="title mb-20 text-white">@lang('Shipping Details')</h4>
                                                <div class="section--bg panel-card-body p-4">
                                                    <div class="row justify-content-center">
                                                        <div class="col-xl-12 col-lg-12 form-group">
                                                            <label>@lang('Name')</label>
                                                            <input class="form--control" name="name" type="text" value="{{ auth()->user()->fullname }}" placeholder="@lang('First Name')" required>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 form-group">
                                                            <label>@lang('Contact No')</label>
                                                            <input class="form--control" name="mobile" type="text" value="{{ auth()->user()->mobile }}" placeholder="@lang('Enter contact no')" required>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 form-group">
                                                            <label>@lang('Email')</label>
                                                            <input class="form--control" name="email" type="email" value="{{ auth()->user()->email }}" placeholder="@lang('Enter valid email')" required>
                                                        </div>
                                                        <div class="col-xl-12 form-group">
                                                            <label>@lang('Full Address')</label>
                                                            <textarea class="form--control" name="address" required placeholder="@lang('Enter full address')" required>{{ old('address', auth()->user()->address) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 mb-30">
                                        <div class="cart-total-area">
                                            <h4 class="title mb-20 text-white">@lang('Total')</h4>
                                            <div class="panel-table-area">
                                                <div class="panel-table">
                                                    <div class="panel-card-body section--bg table-responsive">
                                                        <table class="custom-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('Grand total')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="total-price" data-label="@lang('Grand total')">{{ showAmount($totalPrice) }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="payment-dropdown-area mt-30">
                                                    <h4 class="title mb-20 text-white">@lang('Make Payment')</h4>

                                                    <input name="currency" type="hidden">
                                                    <input name="gateway" type="hidden">
                                                    <input name="amount" type="hidden" value="{{ $totalPrice }}">

                                                    <select class="form--control select2" id="payment-methods">
                                                        @foreach ($gatewayCurrency as $data)
                                                            <option data-methodcode="{{ $data->method_code }}" data-currency="{{ $data->currency }}" value="1">{{ __($data->name) }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="checkout-btn d-flex justify-content-end form-group mt-30">
                                                    <button class="submit-btn w-100" type="submit">@lang('Proceed To Checkout')</button>
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

@push('script')
    <script>
        "use strict";

        (function($) {
            $('#payment-methods').on('change', function() {
                var methodCode = $(this).find('option:selected').data('methodcode');
                var currency = $(this).find('option:selected').data('currency');

                $("input[name=currency]").val(currency);
                $("input[name=gateway]").val(methodCode);

            }).change();
        })(jQuery);
    </script>
@endpush
