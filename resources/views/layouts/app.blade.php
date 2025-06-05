<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Vineshop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased text-gray-800">

@include('partials.header')

<main class="min-h-screen">
    @yield('content')
</main>

@include('partials.footer')

</body>
</html>

