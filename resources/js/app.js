import './bootstrap';
import { createApp } from 'vue';
import Cart from './admin/views/Cart.vue';

// --- Alpine.js ---
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

// --- Swiper ---
import Swiper from 'swiper/bundle'
import 'swiper/css/bundle'

// Якщо ти хочеш ініціалізувати слайдер (наприклад, на Cart.vue), можна зробити тут або у відповідному компоненті
// document.addEventListener('DOMContentLoaded', () => {
//     const swiper = new Swiper('.swiper', {
//         // Налаштування слайдера
//         loop: true,
//         slidesPerView: 1,
//         // ...
//     });
// });

const el = document.getElementById('app');

if (el) {
    const app = createApp({});
    app.component('cart', Cart);
    app.mount('#app');
}
