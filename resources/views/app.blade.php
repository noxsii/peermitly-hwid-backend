<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Peermitly — The fast, beautiful local dev environment</title>
    <meta data-inertia="description" name="description" content="Peermitly is a blazing-fast local development environment for macOS. PHP, Node, Python, Ruby and Go, databases and services — zero config, instant .peer domains with automatic HTTPS.">
    <link data-inertia="canonical" rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#f97316">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_US">
    <meta property="og:site_name" content="Peermitly">
    <meta property="og:title" content="Peermitly — The fast, beautiful local dev environment">
    <meta property="og:description" content="Peermitly is a blazing-fast local development environment for macOS. PHP, Node, Python, Ruby and Go, databases and services — zero config, instant .peer domains with automatic HTTPS.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url('/og-image.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Peermitly — a fast, beautiful local development environment for macOS">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Peermitly — The fast, beautiful local dev environment">
    <meta name="twitter:description" content="Peermitly is a blazing-fast local development environment for macOS. PHP, Node, Python, Ruby and Go, databases and services — zero config, instant .peer domains with automatic HTTPS.">
    <meta name="twitter:image" content="{{ url('/og-image.png') }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <script>
        (function () {
            try {
                const stored = localStorage.getItem('appearance');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isDark = stored === 'dark' || ((stored === null || stored === 'auto') && prefersDark);
                if (isDark) document.documentElement.classList.add('dark');
            } catch (e) {}
        })();
    </script>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "SoftwareApplication",
            "name": "Peermitly",
            "applicationCategory": "DeveloperApplication",
            "operatingSystem": "macOS",
            "url": "{{ config('app.url') }}",
            "offers": {
                "@@type": "Offer",
                "price": "0",
                "priceCurrency": "EUR"
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.ts'])

    @routes
    <x-inertia::head />
</head>
<body>
<x-inertia::app />
</body>
</html>
