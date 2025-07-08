import '../../css/app.css'
import '@/../css/custom.css';

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import router from './router'
import AdminApp from './AdminApp.vue'
import { useAuthStore } from './stores/auth'
import { createI18n } from 'vue-i18n'
import messages from '../lang/messages'
import ToastNotification from './components/ToastNotification.vue'

import { getCookie } from '../utils/cookies'

const app = createApp(AdminApp)
const pinia = createPinia()
app.component('ToastNotification', ToastNotification)

const i18n = createI18n({
    legacy: false,
    locale: getCookie('locale') || 'uk',
    fallbackLocale: 'uk',
    messages,
})

const token = localStorage.getItem('token')
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

app.use(pinia)
app.use(router)
app.use(i18n)

router.beforeEach((to, from, next) => {
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
