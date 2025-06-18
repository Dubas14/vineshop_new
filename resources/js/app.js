import './bootstrap';
import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';
import Cart from './admin/views/Cart.vue';
import messages from './lang/messages';

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
    const i18n = createI18n({
        legacy: false,
        locale: window.APP_LOCALE || 'uk',
        fallbackLocale: 'uk',
        messages,
    });

    const app = createApp({});
    app.component('cart', Cart);
    app.use(i18n);
    app.mount('#app');
}
