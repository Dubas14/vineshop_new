<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Vineshop')</title>
    <script>
        window.DEFAULT_LOCALE = "{{ app()->getLocale() }}";
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

