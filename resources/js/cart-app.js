import { createApp } from 'vue'
import { createI18n } from 'vue-i18n'
import Cart from './admin/views/Cart.vue'
import axios from 'axios'
import uk from './admin/lang/uk.json'
import en from './admin/lang/en.json'

axios.defaults.withCredentials = true

// Додаємо i18n
const i18n = createI18n({
    legacy: false,
    locale: localStorage.getItem('locale') || 'uk',
    messages: { uk, en }
})

const app = createApp(Cart)
app.use(i18n) // <-- обов'язково!
app.mount('#app')
