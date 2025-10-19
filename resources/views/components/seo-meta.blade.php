{{--
    SEO Meta Tags Component
    مكون العلامات الوصفية لتحسين محركات البحث
    
    Usage:
    @include('components.seo-meta', [
        'title' => 'عنوان الصفحة',
        'description' => 'وصف الصفحة',
        'keywords' => 'كلمات, مفتاحية',
        'image' => 'رابط الصورة',
        'url' => 'رابط الصفحة'
    ])
--}}

@php
    $siteTitle = config('app.name', 'تمسيك');
    $pageTitle = isset($title) ? $title . ' - ' . $siteTitle : $siteTitle . ' - منصة إسلامية شاملة';
    $pageDescription = $description ?? 'منصة إسلامية شاملة تجمع خطب وفتاوى ومحاضرات علماء اليمن الأجلاء';
    $pageKeywords = $keywords ?? 'خطب, فتاوى, محاضرات, علماء, إسلام, اليمن, تمسيك';
    $pageImage = $image ?? asset('images/logo.png');
    $pageUrl = $url ?? url()->current();
    $siteName = 'تمسيك';
    $locale = 'ar_YE';
    $type = $type ?? 'website';
@endphp

{{-- Basic Meta Tags --}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="{{ $pageDescription }}">
<meta name="keywords" content="{{ $pageKeywords }}">
<meta name="author" content="{{ $siteName }}">
<meta name="robots" content="index, follow">
<meta name="language" content="Arabic">
<meta name="revisit-after" content="7 days">

{{-- Open Graph Meta Tags (Facebook, LinkedIn) --}}
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:image" content="{{ $pageImage }}">
<meta property="og:url" content="{{ $pageUrl }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $locale }}">

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $pageImage }}">
<meta name="twitter:url" content="{{ $pageUrl }}">

{{-- Additional SEO Meta Tags --}}
<meta name="theme-color" content="#1d8a4e">
<meta name="msapplication-TileColor" content="#1d8a4e">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ $siteName }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $pageUrl }}">

{{-- Favicon --}}
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

{{-- Structured Data (JSON-LD) --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "{{ $type === 'article' ? 'Article' : 'WebSite' }}",
    "name": "{{ $pageTitle }}",
    "description": "{{ $pageDescription }}",
    "url": "{{ $pageUrl }}",
    "image": "{{ $pageImage }}",
    "publisher": {
        "@type": "Organization",
        "name": "{{ $siteName }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo.png') }}"
        }
    },
    "inLanguage": "ar",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ route('search.index') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>

{{-- Additional Structured Data for Organization --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "{{ $siteName }}",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logo.png') }}",
    "description": "{{ $pageDescription }}",
    "sameAs": [
        "https://www.facebook.com/tamsik",
        "https://twitter.com/tamsik",
        "https://www.youtube.com/tamsik"
    ],
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "Customer Service",
        "availableLanguage": ["Arabic"]
    }
}
</script>

{{-- DNS Prefetch & Preconnect للأداء --}}
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

{{-- Preload Critical Resources --}}
<link rel="preload" href="{{ asset('css/unified-theme.css') }}" as="style">
<link rel="preload" href="{{ asset('css/responsive.css') }}" as="style">
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;600;700&family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" as="style">

