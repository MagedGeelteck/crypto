@if($seo)
    {{-- Page Title --}}
    <title>{{ @$seoContents->meta_title ?? gs()->siteName(__($pageTitle)) }} – Anonymous No-KYC Crypto Marketplace</title>
    
    {{-- Meta Description --}}
    <meta name="description" content="{{ @$seoContents->description ?? $seo->description ?? 'CryptoOnion is a privacy-focused, no-KYC cryptocurrency marketplace available on both clearnet and Tor. Fully anonymous Bitcoin transfers, secure onion routing, and trusted deep-web ecommerce.' }}">
    
    {{-- Meta Keywords --}}
    <meta name="keywords" content="{{ implode(',',@$seoContents->keywords ?? $seo->keywords) }}, anonymous crypto marketplace, no KYC bitcoin, onion crypto exchange, private crypto trading, deep web ecommerce, tor marketplace, secure crypto payments">
    
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">

    {{--<!-- Apple Stuff -->--}}
    <link rel="apple-touch-icon" href="{{ siteLogo() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ gs()->siteName('') }}">
    
    {{--<!-- Robots & Viewport -->--}}
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    {{--<!-- Google / Search Engine Tags -->--}}
    <meta itemprop="name" content="{{ gs()->siteName(__($pageTitle)) }}">
    <meta itemprop="description" content="{{ @$seoContents->description ?? $seo->description }}">
    <meta itemprop="image" content="{{ $seoImage ?? getImage(getFilePath('seo') .'/'. $seo->image) }}">
    
    {{--<!-- Facebook Meta Tags / OpenGraph -->--}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ gs()->siteName('') }}">
    <meta property="og:title" content="{{ @$seoContents->social_title ?? $seo->social_title ?? gs()->siteName(__($pageTitle)) }} – Private No-KYC Crypto Marketplace">
    <meta property="og:description" content="{{ @$seoContents->social_description ?? $seo->social_description ?? 'Access our anonymous deep-web crypto marketplace. Secure, trusted, and privacy-first.' }}">
    <meta property="og:image" content="{{ $seoImage ?? getImage(getFilePath('seo') .'/'. $seo->image) }}">
    <meta property="og:image:type" content="image/{{ pathinfo(getImage(getFilePath('seo')) .'/'. $seo->image)['extension'] }}">
    @php $socialImageSize = explode('x', getFileSize('seo')) @endphp
    <meta property="og:image:width" content="{{ $socialImageSize[0] }}">
    <meta property="og:image:height" content="{{ $socialImageSize[1] }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="en_US">
    
    {{--<!-- Twitter Meta Tags -->--}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ @$seoContents->social_title ?? $seo->social_title ?? gs()->siteName(__($pageTitle)) }} – Private Crypto Marketplace">
    <meta name="twitter:description" content="{{ @$seoContents->social_description ?? $seo->social_description ?? 'Anonymous, secure, Tor-optimized crypto commerce with no KYC.' }}">
    <meta name="twitter:image" content="{{ $seoImage ?? getImage(getFilePath('seo') .'/'. $seo->image) }}">
    
    {{--<!-- Additional SEO Meta Tags -->--}}
    <meta name="classification" content="Financial Services, Cryptocurrency Marketplace">
    <meta name="category" content="E-commerce, Cryptocurrency, Privacy">
    <meta name="coverage" content="Worldwide">
    <meta name="distribution" content="Global">
    <meta name="rating" content="General">
    
    {{--<!-- Schema.org JSON-LD – WebSite with Search + Onion Mirror -->--}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ gs()->siteName('') }} Marketplace",
        "url": "{{ config('app.url') }}",
        "sameAs": [
            "http://mtidtmncruzy4k3p5jhthhsmm3vohsxxb2vayjicntykoy4lwcl7gvqd.onion/"
        ],
        "description": "Anonymous, no-KYC cryptocurrency marketplace available on both clearnet and Tor network. Privacy-focused digital services.",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ route('products') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        },
        "keywords": "anonymous crypto marketplace, no KYC bitcoin, onion crypto exchange, private crypto trading, deep web ecommerce, tor marketplace, secure crypto payments"
    }
    </script>
    
    {{--<!-- Schema.org JSON-LD – FinancialService / Deep Web Service -->--}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FinancialService",
        "name": "{{ gs()->siteName('') }}",
        "alternateName": "{{ gs()->siteName('') }} .onion Marketplace",
        "url": "{{ config('app.url') }}",
        "logo": "{{ siteLogo() }}",
        "image": "{{ $seoImage ?? getImage(getFilePath('seo') .'/'. $seo->image) }}",
        "description": "Anonymous, no-KYC cryptocurrency marketplace and ecommerce service on the Tor network. Fully private Bitcoin transactions with secure onion routing.",
        "currenciesAccepted": "BTC",
        "paymentAccepted": "Bitcoin, Cryptocurrency",
        "priceRange": "$$",
        "areaServed": "Worldwide",
        "serviceType": "Anonymous Crypto Services",
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Digital Products & Crypto Services",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Anonymous Cryptocurrency Trading",
                        "description": "Privacy-focused, no-KYC Bitcoin and cryptocurrency marketplace for secure digital transactions"
                    }
                }
            ]
        }
    }
    </script>
    
    {{--<!-- Schema.org JSON-LD – Organization -->--}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ gs()->siteName('') }}",
        "url": "{{ config('app.url') }}",
        "logo": "{{ siteLogo() }}",
        "description": "Leading anonymous cryptocurrency marketplace providing secure, privacy-focused digital services on both clearnet and Tor network with no-KYC requirements",
        "areaServed": "Worldwide",
        "knowsAbout": ["Cryptocurrency", "Bitcoin", "Privacy", "Anonymous Trading", "Secure Transactions", "Deep Web Commerce", "Tor Network", "Onion Services"]
    }
    </script>
@endif
