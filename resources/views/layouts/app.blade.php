<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Vineshop')</title>
    <script>
        let storedLocale = localStorage.getItem('locale');
        if (!storedLocale) {
            storedLocale = 'uk';
            localStorage.setItem('locale', storedLocale);
        }
        document.documentElement.lang = storedLocale;
    </script>
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800">
<div class="absolute top-0 left-0 p-2 bg-gray-200 text-xs">
    {{ app()->getLocale() }}
</div>
@include('partials.header')
@include('partials.alerts')

<main class="min-h-screen">
    @yield('content')
</main>

@include('partials.footer')

</body>
</html>

