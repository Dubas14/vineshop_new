<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Vineshop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

