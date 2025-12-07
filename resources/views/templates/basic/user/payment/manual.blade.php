@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <section class="pb-60">
        <div class="container">
            {{-- Checkout Progress Tracker --}}
            <div class="checkout-progress-wrapper mb-5">
                <div class="checkout-progress">
                    <div class="progress-step completed">
                        <div class="step-icon">
                            <i class="las la-check"></i>
                        </div>
                        <div class="step-label">Cart</div>
                    </div>
                    <div class="progress-line completed"></div>
                    <div class="progress-step active completed">
                        <div class="step-icon">
                            <i class="lab la-bitcoin"></i>
                        </div>
                        <div class="step-label">Payment</div>
                    </div>
                    <div class="progress-line active"></div>
                    <div class="progress-step active">
                        <div class="step-icon">
                            <i class="las la-check-circle"></i>
                        </div>
                        <div class="step-label">Confirmation</div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card custom--card">
                        <div class="card-header card-header-bg">
                            <h5 class="card-title">{{ __($pageTitle) }}</h5>
                        </div>
                        <div class="card-body">
                            <form class="disableSubmission" action="{{ route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-warning">
                                            <p class="mb-0"><i class="las la-exclamation-triangle"></i> <strong>@lang('Important Note'):</strong> @lang('Be Accurate When You Provide Your Email Account For Paypal, If You Miss it You\'ll Miss Your Payment. We\'re Transferring from Real Accounts No Banned Or Hacked Accounts. You Can Make Up to 2 Transactions Per Day.')
                                            </p>
                                        </div>

                                        <div class="alert alert-info">
                                            <h6 class="mb-2"><i class="lab la-bitcoin"></i> <strong>@lang('BTC Payment Instructions')</strong></h6>
                                            <ol class="mb-0 ps-3">
                                                <li>
                                                    @lang('Send Exactly') <b>₿{{ showAmount($data['final_amount'], currencyFormat: false) }} BTC</b> @lang('To This BTC Address:') 
                                                    <b>12EnY2zjRAszBsj2qoxDTwPLMBGStrTwcq</b>
                                                    <button type="button" class="btn btn-sm btn-dark ms-1" onclick="copyBTCAddress()" title="Copy BTC Address">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </li>
                                                <li>@lang('Enter your PayPal Account Email below') <span class="badge bg-danger">@lang('required')</span></li>
                                                <li>@lang('Enter your BTC Transaction ID') <span class="badge bg-success">@lang('optional')</span> (@lang('recommended for faster processing'))</li>
                                                <li>@lang('Click "Pay Now" to submit your payment confirmation')</li>
                                                <li>@lang('You\'ll Receive Order Confirmation Email')</li>
                                                <li>@lang('Wait For Payment Confirmation - You Will Receive Your Amount Smoothly Within') <span class="badge bg-danger">2H</span> @lang('Max')</li>
                                            </ol>
                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">@lang('PayPal Account Email') <span class="text-danger">*</span></label>
                                            <input type="email" name="paypal_email" class="form-control" value="{{ old('paypal_email') }}" required placeholder="@lang('Enter your PayPal account email')">
                                        </div>
                                    </div>

                                    <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button class="btn btn--base w-100" type="submit"><i class="las la-lock"></i> @lang('Pay Now')</button>
                                        </div>
                                        <p class="mt-3 mb-0 text-center fw-bold"><i class="las la-info-circle"></i> @lang('Something Went Wrong ?') <a href="{{ route('contact') }}" class="fw-bold" style="color: #06a3f4;">@lang('Open Support Ticket')</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
<script>
function copyBTCAddress() {
    const btcAddress = '12EnY2zjRAszBsj2qoxDTwPLMBGStrTwcq';
    
    // Create a temporary input element
    const tempInput = document.createElement('input');
    tempInput.value = btcAddress;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    
    // Show notification
    iziToast.success({
        message: 'BTC Address copied to clipboard!',
        position: "topRight"
    });
}
</script>
@endpush
