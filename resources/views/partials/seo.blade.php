@if($seo)
    <meta name="title" Content="{{ gs()->siteName(__($pageTitle)) }}">
    <meta name="description" content="{{ @$seoContents->description ?? $seo->description }}">
    <meta name="keywords" content="{{ implode(',',@$seoContents->keywords ?? $seo->keywords) }}">
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">

    {{--<!-- Apple Stuff -->--}}
    <link rel="apple-touch-icon" href="{{ siteLogo() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ gs()->siteName($pageTitle) }}">
    
    {{--<!-- Google / Search Engine Tags -->--}}
    <meta itemprop="name" content="{{ gs()->siteName($pageTitle) }}">
    <meta itemprop="description" content="{{ @$seoContents->description ?? $seo->description }}">
    <meta itemprop="image" content="{{ $seoImage ?? getImage(getFilePath('seo') .'/'. $seo->image) }}">
    
    {{--<!-- Facebook Meta Tags -->--}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ gs()->siteName('') }}">
    <meta property="og:title" content="{{ @$seoContents->social_title ?? $seo->social_title }}">
    <meta property="og:description" content="{{ @$seoContents->social_description ?? $seo->social_description }}">
    <meta property="og:image" content="{{ $seoImage ?? getImage(getFilePath('seo') .'/'. $seo->image) }}">
    <meta property="og:image:type" content="image/{{ pathinfo(getImage(getFilePath('seo')) .'/'. $seo->image)['extension'] }}">
    @php $socialImageSize = explode('x', getFileSize('seo')) @endphp
    <meta property="og:image:width" content="{{ $socialImageSize[0] }}">
    <meta property="og:image:height" content="{{ $socialImageSize[1] }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="en_US">
    
    {{--<!-- Twitter Meta Tags -->--}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ @$seoContents->social_title ?? $seo->social_title }}">
    <meta name="twitter:description" content="{{ @$seoContents->social_description ?? $seo->social_description }}">
    <meta name="twitter:image" content="{{ $seoImage ?? getImage(getFilePath('seo') .'/'. $seo->image) }}">
    
    {{--<!-- Darknet & Privacy Meta Tags -->--}}
    <meta name="robots" content="index, follow">
    <meta name="classification" content="Financial Services, Cryptocurrency Marketplace">
    <meta name="category" content="E-commerce, Cryptocurrency, Privacy">
    <meta name="coverage" content="Worldwide">
    <meta name="distribution" content="Global">
    <meta name="rating" content="General">
    
    {{--<!-- Schema.org JSON-LD Markup for Financial Service -->--}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FinancialService",
        "name": "{{ gs()->siteName('') }}",
        "description": "{{ @$seoContents->description ?? $seo->description }}",
        "url": "{{ url()->current() }}",
        "logo": "{{ siteLogo() }}",
        "image": "{{ $seoImage ?? getImage(getFilePath('seo') .'/'. $seo->image) }}",
        "priceRange": "$$",
        "paymentAccepted": "Bitcoin, Cryptocurrency",
        "currenciesAccepted": "BTC",
        "areaServed": "Worldwide",
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Digital Products & Services",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Anonymous Crypto Marketplace",
                        "description": "Privacy-focused cryptocurrency marketplace for secure digital transactions"
                    }
                }
            ]
        }
    }
    </script>
    
    {{--<!-- Schema.org JSON-LD Markup for Crypto Marketplace -->--}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ gs()->siteName('') }}",
        "description": "Anonymous crypto marketplace - Privacy-first secure onion service for no-KYC cryptocurrency transactions on the deep web",
        "url": "{{ config('app.url') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ route('products') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        },
        "keywords": "anonymous crypto, privacy marketplace, secure onion service, no-KYC crypto, deep web digital marketplace, cryptocurrency exchange, Bitcoin marketplace, darknet market, privacy-focused trading"
    }
    </script>
    
    {{--<!-- Schema.org JSON-LD Markup for Organization -->--}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ gs()->siteName('') }}",
        "url": "{{ config('app.url') }}",
        "logo": "{{ siteLogo() }}",
        "description": "Leading anonymous cryptocurrency marketplace providing secure, privacy-focused digital services on the deep web with no-KYC requirements",
        "areaServed": "Worldwide",
        "knowsAbout": ["Cryptocurrency", "Bitcoin", "Privacy", "Anonymous Trading", "Secure Transactions", "Deep Web Commerce"]
    }
    </script>
@endif
