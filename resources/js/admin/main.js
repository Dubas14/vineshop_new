import '../../css/app.css' // ✅ Підключення TailwindCSS

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import router from './router'
import AdminApp from './AdminApp.vue'
import { useAuthStore } from './stores/auth' // ← адаптуй шлях якщо треба
import { createI18n } from 'vue-i18n'
import en from './lang/en.json'
import uk from './lang/uk.json'


const app = createApp(AdminApp)
const pinia = createPinia()

const messages = { en, uk }
const i18n = createI18n({
    legacy: false,
    locale: localStorage.getItem('locale') || 'uk',
    messages,
})

const token = localStorage.getItem('token')
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

app.use(pinia)
app.use(router)
app.use(i18n)

// Використовуй офіційний API Pinia для доступу до store
router.beforeEach((to, from, next) => {
    // ЩОБ працювало ПРАВИЛЬНО — викликай useAuthStore З pinia:
    const auth = useAuthStore(pinia)
    const isAuth = auth.isAuthenticated ?? !!localStorage.getItem('token')

    if (to.name !== 'login' && !isAuth) {
        next({ name: 'login' })
    } else if (to.name === 'login' && isAuth) {
        next({ name: 'dashboard' })
    } else {
        next()
    }
})

app.mount('#admin')
