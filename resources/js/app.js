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

// Глобальна ініціалізація слайдера для банерів
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.mySwiperBanner')) {
        new Swiper('.mySwiperBanner', {
            loop: true,
            autoplay: {
                delay: 5000,
            },
            // ...інші налаштування
        });
    }
});

const el = document.getElementById('app');

if (el) {
    const app = createApp({});
    app.component('cart', Cart);
    app.mount('#app');
}
