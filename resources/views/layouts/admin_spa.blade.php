<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
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
    @vite('resources/js/admin/main.js')
</head>
<body>
<div id="admin"></div>
</body>
</html>
