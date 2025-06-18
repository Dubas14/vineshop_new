import '../../css/app.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import router from './router'
import AdminApp from './AdminApp.vue'
import { useAuthStore } from './stores/auth'
import { createI18n } from 'vue-i18n'
import messages from '../lang/messages'

const getCookie = (name) => {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : null;
};

const app = createApp(AdminApp)
const pinia = createPinia()

const i18n = createI18n({
    legacy: false,
    locale: getCookie('locale') || window.DEFAULT_LOCALE || 'uk',
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
