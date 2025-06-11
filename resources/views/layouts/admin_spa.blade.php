<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script>
        const sessionLocale = '{{ app()->getLocale() }}'
        if (localStorage.getItem('locale') !== sessionLocale) {
            localStorage.setItem('locale', sessionLocale)
        }
    </script>
    @vite('resources/js/admin/main.js')
</head>
<body>
<div id="admin"></div>
</body>
</html>
