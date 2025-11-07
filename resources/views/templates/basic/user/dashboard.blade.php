@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-area mt-30">

        <div class="notice"></div>

        <div class="panel-card-header section--bg text-white">
            <div class="panel-card-title"><i class="las la-cloud-upload-alt"></i> @lang('User Activity')</div>
        </div>
        <div class="panel-card-body">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item bg--success box-shadow--success">
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-wallet"></i>
                            </div>
                            <div class="num text-white" data-start="0" data-end="0" data-postfix="" data-duration="1500" data-delay="0">{{ $completedOrders }}</div>
                            <h3 class="title text-white">@lang('Completed Orders')</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item bg--warning box-shadow--warning">
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-clipboard-check"></i>
                            </div>
                            <div class="num text-white" data-start="0" data-end="0" data-postfix="" data-duration="1500" data-delay="0">{{ $processingOrders }}</div>
                            <h3 class="title text-white">@lang('Processing Orders')</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 col-sm-6 mb-30">
                    <div class="dashboard-item bg--danger box-shadow--danger">
                        <div class="dashboard-content">
                            <div class="dashboard-icon">
                                <i class="las la-spinner"></i>
                            </div>
                            <div class="num text-white" data-start="0" data-end="0" data-postfix="" data-duration="1500" data-delay="0">{{ $supportTickets }}</div>
                            <h3 class="title text-white">@lang('Total Support Tickets')</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
