@php
    $footerContent = getContent('footer.content',true);
    $policyPages = getContent('policy_pages.element', orderById:true);
    $contactElement = getContent('contact_us.element',orderById:true);
@endphp
    <footer class="footer-section modern-footer pt-60">
        <div class="footer-bg-layer"></div>
        <div class="container-fluid px-0">
            <div class="footer-inner container-xl">
                <div class="footer-brand-row text-center mb-40">
                    <a class="footer-logo" href="{{route('home')}}"><img src="{{ siteLogo() }}" alt="@lang('logo')"></a>
                </div>
                <div class="row gy-4 footer-grid">
                    <div class="col-xl-12 col-md-12 text-center">
                        <div class="footer-block about-block">
                            <h2 class="footer-text">{{__(@$footerContent->data_values->details)}}</h2>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6" hidden>
                        <div class="footer-block links-block">
                            <h3 class="footer-title">@lang('Explore')</h3>
                            <ul class="footer-links list-unstyled">
                                <li><a href="{{route('home')}}">@lang('Home')</a></li>
                                <li><a href="{{route('products')}}">@lang('Products')</a></li>
                                <li><a href="{{route('contact')}}">@lang('Support')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6" hidden>
                        <div class="footer-block policy-block">
                            <h3 class="footer-title">@lang('Policies')</h3>
                            <ul class="footer-links list-unstyled">
                                @foreach ($policyPages as $policy)
                                    <li><a href="{{ route('policy.pages', $policy->slug) }}">@lang(@$policy->data_values->title)</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6" >
                        <div class="footer-block contact-block" hidden>
                            <h3 class="footer-title">@lang('Contact')</h3>
                            <ul class="footer-contact list-unstyled">
                                @foreach($contactElement as $item)
                                    @php $heading = strtolower(@$item->data_values->heading); @endphp
                                    @continue(str_contains($heading, 'phone') || str_contains($heading, 'telephone') || str_contains($heading, 'mobile'))
                                    <li class="d-flex gap-2 mb-2">
                                        <span class="contact-label fw-semibold">{{__(@$item->data_values->heading)}}:</span>
                                        <span class="contact-value">{{__(@$item->data_values->details)}}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom mt-50">
                <div class="container-xl">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-3 copyright-wrap">
                        <p class="mb-0 small">© 2019 - {{ now()->format('Y') }} {{ gs('site_name') }} ·</p>
                        <p class="mb-0 small">@lang('Built for performance & accessibility')</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
