import './bootstrap';
import { createApp } from 'vue';
import Cart from './admin/views/Cart.vue';

const app = createApp({});
app.component('cart', Cart);
app.mount('#app');
