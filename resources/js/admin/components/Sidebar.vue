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
    try {
        await axios.post('/api/set-locale', { locale: lang });
        document.cookie = `locale=${lang};path=/;max-age=${60 * 60 * 24 * 365}`;
    } catch (e) {}
};
</script>

<template>
    <div v-if="auth.isAuthenticated" class="sidebar">
        <div class="sidebar-header">
            <h3>VineShop Admin</h3>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><router-link to="/admin/dashboard"><i class="icon-dashboard"></i>{{ $t('dashboard') }}</router-link></li>
                <li><router-link to="/admin/products"><i class="icon-products"></i>{{ $t('products') }}</router-link></li>
                <li><router-link to="/admin/orders"><i class="icon-orders"></i>{{ $t('orders') }}</router-link></li>
                <li><router-link to="/admin/categories"><i class="icon-categories"></i>{{ $t('categories') }}</router-link></li>
                <li><router-link to="/admin/banners"><i class="icon-banners"></i>{{ $t('banners') }}</router-link></li>
                <li><router-link to="/admin/import-products"><i class="icon-import"></i>{{ $t('import_products') }}</router-link></li>
                <li><router-link to="/admin/import-images"><i class="icon-images"></i>{{ $t('import_images') }}</router-link></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <div class="language-switcher">
                <button @click="changeLang('uk')" :class="{ active: locale === 'uk' }">UA</button>
                <button @click="changeLang('en')" :class="{ active: locale === 'en' }">EN</button>
            </div>
            <a href="#" @click.prevent="logout" class="logout-btn"><i class="icon-logout"></i>{{ $t('logout') }}</a>
        </div>
    </div>
</template>

<style scoped>
/* Only minimal styles here, main styles in CSS file */
.sidebar {
    display: flex;
    flex-direction: column;
}
</style>
