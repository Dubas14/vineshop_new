<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Vineshop')</title>
    <script>
        window.APP_LOCALE = "{{ app()->getLocale() }}";
        document.documentElement.lang = window.APP_LOCALE;
        localStorage.setItem('locale', window.APP_LOCALE);
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

