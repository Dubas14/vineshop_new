import './bootstrap';
import { createApp } from 'vue';
import Cart from './admin/views/Cart.vue';

const el = document.getElementById('app');

if (el) {
    const app = createApp({});
    app.component('cart', Cart);
    app.mount('#app');
}
