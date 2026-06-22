<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

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

    @vite(['resources/css/app.css', 'resources/js/app.ts'])

    @routes
    <x-inertia::head />
</head>
<body>
<x-inertia::app />
</body>
</html>
