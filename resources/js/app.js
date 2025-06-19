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
    const getCookie = (name) => {
        const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
        return match ? decodeURIComponent(match[1]) : null;
    };
    const i18n = createI18n({
        legacy: false,
        locale: getCookie('locale') || 'uk',
        fallbackLocale: 'uk',
        messages,
    });

    const app = createApp({});
    app.component('cart', Cart);
    app.use(i18n);
    app.mount('#app');
}
