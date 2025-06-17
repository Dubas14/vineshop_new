<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script>
        window.APP_LOCALE = "{{ app()->getLocale() }}";
    </script>
    @vite('resources/js/admin/main.js')
</head>
<body>
<div id="admin"></div>
</body>
</html>
