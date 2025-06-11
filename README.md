# Vineshop (Laravel + Vue SPA)

## 🚀 Встановлення

```bash
git clone https://github.com/your-username/vineshop.git
cd vineshop

# Backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```
# Frontend
npm install
npm run dev

# Translation
Create translation files in resources/lang and resources/js/admin/lang.
After updating translations run:
npm run build

# Запуск
php artisan serve

# Додатково
php artisan migrate --seed   # якщо потрібно створити таблиці й залити тестові дані
