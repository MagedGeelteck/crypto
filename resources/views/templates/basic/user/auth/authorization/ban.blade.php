@extends($activeTemplate . 'layouts.frontend')

@php
    $bannedContent = getContent('banned.content', true);
@endphp

@section('content')
    <section class="pb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card custom--card">
                        <div class="card-body p-0">
                            <div class="banned-image">
                                <img src="{{ frontendImage('banned', $bannedContent->data_values->image) }}" alt="image">
                            </div>
                            <div class="mt-5 text-center">
                                <h3 class="text--danger pb-2">@lang('You are banned')</h3>
                                <p class="fw-bold mb-1">@lang('Reason'):</p>
                                <p>{{ $user->ban_reason }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .banned-image {
            max-width: 400px;
            text-align: center;
            margin: 0 auto;
        }

        .banned-image img {
            width: 100%;
        }

        .card.custom--card {
            border: none;
        }
    </style>
@endpush
