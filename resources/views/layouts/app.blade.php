<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Vineshop')</title>
    <script>
        const sessionLocale = '{{ app()->getLocale() }}'
        if (localStorage.getItem('locale') !== sessionLocale) {
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

