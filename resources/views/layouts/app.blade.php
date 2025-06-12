<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Vineshop')</title>
    <script>
        let storedLocale = localStorage.getItem('locale');
        if (!storedLocale) {
            storedLocale = 'uk';
            localStorage.setItem('locale', storedLocale);
        }
        document.documentElement.lang = storedLocale;
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

