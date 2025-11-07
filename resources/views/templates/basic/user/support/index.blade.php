@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="transaction-area mt-30">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="text-end">
                    <a href="{{route('ticket.open') }}" class="btn btn--base"> <i class="fas fa-plus"></i> @lang('New Ticket')</a>
                </div>
                <div class="panel-table-area mt-20">
                    <div class="panel-table">
                        <div class="panel-card-body section--bg table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>@lang('Subject')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Priority')</th>
                                        <th>@lang('Last Reply')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supports as $support)
                                        <tr>
                                            <td> <a class="fw-bold" href="{{ route('ticket.view', $support->ticket) }}"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                                            <td>
                                                @php echo $support->statusBadge; @endphp
                                            </td>
                                            <td>
                                                @if ($support->priority == Status::PRIORITY_LOW)
                                                    <span class="badge badge--dark">@lang('Low')</span>
                                                @elseif($support->priority == Status::PRIORITY_MEDIUM)
                                                    <span class="badge badge--warning">@lang('Medium')</span>
                                                @elseif($support->priority == Status::PRIORITY_HIGH)
                                                    <span class="badge badge--danger">@lang('High')</span>
                                                @endif
                                            </td>
                                            <td>{{ diffForHumans($support->last_reply) }} </td>

                                            <td>
                                                <a class="badge badge--success text-white" href="{{ route('ticket.view', $support->ticket) }}">
                                                    @lang('Details')
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center justify-content-center no-data-table" colspan="100%">@lang('No data found')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($supports->hasPages())
                        <div class="paginate-warper">
                                {{ paginateLinks($supports) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
