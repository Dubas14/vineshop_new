import './bootstrap';
import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';
import Cart from './admin/views/Cart.vue';
import messages from './lang/messages';
import { getCookie } from './utils/cookies';
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()


import Swiper from 'swiper/bundle'
import 'swiper/css/bundle'


document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.mySwiperBanner')) {
        new Swiper('.mySwiperBanner', {
            loop: true,
            autoplay: {
                delay: 5000,
            },
        });
    }
});

const el = document.getElementById('app');

if (el) {
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
