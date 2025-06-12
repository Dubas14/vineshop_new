<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Vineshop')</title>
    <script>
        const sessionLocale = '{{ app()->getLocale() }}'
        const storedLocale = localStorage.getItem('locale')

        if (
            storedLocale &&
            storedLocale !== sessionLocale &&
            !window.location.pathname.startsWith('/lang/')
        ) {
            window.location.href = `/lang/${storedLocale}`
        } else if (!storedLocale) {
            localStorage.setItem('locale', sessionLocale)
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800">

@include('partials.header')
@include('partials.alerts')

<main class="min-h-screen">
    @yield('content')
</main>

@include('partials.footer')

</body>
</html>

