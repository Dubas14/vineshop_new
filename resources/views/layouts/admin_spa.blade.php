<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script>
        let storedLocale = localStorage.getItem('locale');
        if (!storedLocale) {
            storedLocale = 'uk';
            localStorage.setItem('locale', storedLocale);
        }
        document.documentElement.lang = storedLocale;
    </script>
    @vite('resources/js/admin/main.js')
</head>
<body>
<div id="admin"></div>
</body>
</html>
