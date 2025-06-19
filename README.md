# Vineshop (Laravel + Vue SPA)

🍷 Інтернет-магазин на Laravel + Vue.js (SPA-адмінка)

---

## 🚀 Встановлення

```bash
git clone https://github.com/your-username/vineshop.git
cd vineshop

# 🔧 Backend
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
touch database/database.sqlite
php artisan migrate --seed
php artisan lang:export-js   # генерує resources/js/lang/messages.js із Laravel-перекладів
node check-missing-i18n.mjs  # перевірка на відсутні ключі в i18n

# 🎨 Frontend
npm install
npm run dev


