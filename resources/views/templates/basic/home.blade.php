@extends($activeTemplate.'layouts.frontend')
@section('content')
    
    @include('Template::sections.banner')

    @if($sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif

    {{-- SEO Content Section for Search Engine Optimization --}}
    <section class="seo-content-section" hidden aria-hidden="true">
        <h1>Anonymous Crypto Marketplace – Secure Deep-Web Bitcoin Services</h1>
        <p>Your trusted onion-routed cryptocurrency commerce hub offering verified, private, and anonymous Bitcoin transactions.</p>
        
        <p>CryptoOnion is a <strong>privacy-focused, no-KYC cryptocurrency marketplace</strong> available on both clearnet and Tor network. Fully <strong>anonymous Bitcoin transfers</strong>, secure onion routing, and trusted <strong>deep-web ecommerce</strong>.</p>
        
        <h2>Anonymous Crypto Trading Platform</h2>
        <p>Experience true financial freedom with our <strong>no-KYC crypto</strong> exchange. Trade Bitcoin and other cryptocurrencies without identity verification.</p>
        
        <ul>
            <li><strong>Anonymous crypto marketplace</strong> with complete privacy protection</li>
            <li><strong>No KYC bitcoin</strong> purchases - no identity verification required</li>
            <li><strong>Onion crypto exchange</strong> accessible through Tor network</li>
            <li><strong>Private crypto trading</strong> with military-grade encryption</li>
            <li><strong>Deep web ecommerce</strong> for secure digital transactions</li>
            <li><strong>Tor marketplace</strong> with instant Bitcoin payments</li>
            <li><strong>Secure crypto payments</strong> and anonymous transfers</li>
        </ul>
    </section>

@endsection
