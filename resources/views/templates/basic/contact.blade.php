@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $contactContent = getContent('contact_us.content', true);
        $contactElement = getContent('contact_us.element');
    @endphp

    <div class="contact-section">
        <div class="container">
            <div class="contact-area">
                <div class="row justify-content-center mb-30-none">
                    <div class="col-lg-4 mb-30">
                        <div class="contact-info-item-area mb-40-none">
                            <div class="contact-info-header mb-30">
                                <h3 class="header-title">{{ __(@$contactContent->data_values->heading_one) }}</h3>
                            </div>
                            @foreach ($contactElement as $item)
                                <div class="contact-info-item d-flex align-items-center mb-40 flex-wrap">
                                    <div class="contact-info-icon">
                                        @php echo @$item->data_values->icon @endphp
                                    </div>
                                    <div class="contact-info-content">
                                        <h3 class="title">{{ __(@$item->data_values->heading) }}</h3>
                                        <p>{{ __(@$item->data_values->details) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-8 mb-30">
                        <div class="contact-form-area">
                            <h3 class="title">{{ __(@$contactContent->data_values->heading_two) }}</h3>
                            <form class="contact-form" method="post">
                                @csrf
                                <div class="row justify-content-center mb-10-none">
                                    <div class="col-lg-12 form-group">
                                        <input class="form-control" name="name" type="text" value="{{ old('name', @$user->fullname) }}" placeholder="@lang('Your name')" @if ($user && $user->profile_complete) readonly @endif required>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <input class="form-control" name="email" type="email" value="{{ old('email', @$user->email) }}" placeholder="@lang('Your Email')" @if ($user) readonly @endif required>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <input class="form-control" name="subject" type="text" value="{{ old('subject') }}" placeholder="@lang('Your subject')" required>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <textarea class="form-control" name="message" required placeholder="@lang('Your message')">{{ old('message') }}</textarea>
                                    </div>

                                    @php
                                        $custom = true;
                                    @endphp

                                    <x-captcha :custom="$custom" />

                                    <div class="col-lg-12 form-group mb-0">
                                        <button class="submit-btn" type="submit">@lang('Send Message')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="map-section ptb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="maps" id="map"></div>
                </div>
            </div>
        </div>
    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection

@push('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ @$contactContent->data_values->map_key }}&callback=initMap&libraries=&v=weekly" async></script>
    <script>
        // Initialize and add the map
        function initMap() {
            // The location of Uluru
            const uluru = {
                lat: {{ @$contactContent->data_values->latitude }},
                lng: {{ @$contactContent->data_values->longitude }}
            };
            // The map, centered at Uluru
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 5,
                center: uluru,
            });
            // The marker, positioned at Uluru
            const marker = new google.maps.Marker({
                position: uluru,
                map: map,
            });
        }
    </script>
@endpush
