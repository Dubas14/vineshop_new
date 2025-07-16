import './bootstrap';
import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';
import Cart from './admin/views/Cart.vue';
import SearchAutocomplete from './components/SearchAutocomplete.vue';
import messages from './lang/messages';
import { getCookie } from './utils/cookies';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.mySwiperBanner')) {
        new Swiper('.mySwiperBanner', {
            loop: true,
            autoplay: {
                delay: 5000,
            },
        });
    }

    const i18n = createI18n({
        legacy: false,
        locale: getCookie('locale') || 'uk',
        fallbackLocale: 'uk',
        messages,
    });

    // Якщо є корзина або інші речі в #app
    const elApp = document.getElementById('app');
    if (elApp) {
        const app = createApp({});
        app.use(i18n);
        app.component('cart', Cart);
        app.mount('#app');
    }

    // Якщо є пошук окремо
    const elSearch = document.getElementById('vue-search');
    if (elSearch) {
        const searchApp = createApp({});
        searchApp.use(i18n);
        searchApp.component('search-autocomplete', SearchAutocomplete);
        searchApp.mount('#vue-search');
    }
});
