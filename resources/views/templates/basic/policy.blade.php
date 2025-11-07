@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pb-60">
        <div class="container">
            @php
                echo $policy->data_values->details;
            @endphp
        </div>
    </section>
@endsection
