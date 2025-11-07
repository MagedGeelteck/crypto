@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pb-60">
        <div class="container">
            he
            @php
                echo $cookie->data_values->description;
            @endphp
        </div>
    </section>
@endsection
