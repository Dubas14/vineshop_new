<script setup>
import { useAuthStore } from '@/admin/stores/auth'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import axios from 'axios'

const auth = useAuthStore()
const router = useRouter()
const { locale, t } = useI18n()

const logout = async () => {
    await axios.post('/api/admin/logout').catch(() => {})
    auth.logout()
    router.push({ name: 'login' })
}

const changeLang = async (lang) => {
    locale.value = lang;
    localStorage.setItem('locale', lang);
    try {
        await axios.get(`/lang/${lang}`);
    } finally {
        window.location.reload();
    }
};
</script>

<template>
    <div v-if="auth.isAuthenticated" class="sidebar">
        <!-- посилання -->
        <ul>
            <li><router-link to="/admin/dashboard">{{ $t('dashboard') }}</router-link></li>
            <li><router-link to="/admin/products">{{ $t('products') }}</router-link></li>
            <li><router-link to="/admin/orders">{{ $t('orders') }}</router-link></li>
            <li><router-link to="/admin/categories">{{ $t('categories') }}</router-link></li>
            <li><router-link to="/admin/banners">{{ $t('banners') }}</router-link></li>
            <li><a href="#" @click.prevent="logout" style="color: red;">{{ $t('logout') }}</a></li>
            <li class="mt-2">
                <button @click="changeLang('uk')" class="mr-1" :class="{ 'font-bold': locale === 'uk' }">UA</button>
                <button @click="changeLang('en')" :class="{ 'font-bold': locale === 'en' }">EN</button>
            </li>
        </ul>
    </div>
</template>

<style scoped>
.sidebar {
    width: 200px;
    padding: 1rem;
    border: 1px solid red;
}
</style>
