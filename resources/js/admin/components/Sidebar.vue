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
    locale.value = lang
    try {
        await axios.post('/api/set-locale', { locale: lang })
        document.cookie = `locale=${lang};path=/;max-age=${60 * 60 * 24 * 365}`
    } catch (e) {}
}
</script>

<template>
    <div v-if="auth.isAuthenticated" class="sidebar">
        <div class="sidebar-header">
            <h3>VineShop Admin</h3>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <router-link to="/admin/dashboard" class="icon-dashboard">
                        <span>{{ $t('dashboard') }}</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/admin/products" class="icon-products">
                        <span>{{ $t('products') }}</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/admin/orders" class="icon-orders">
                        <span>{{ $t('orders') }}</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/admin/categories" class="icon-categories">
                        <span>{{ $t('categories') }}</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/admin/banners" class="icon-banners">
                        <span>{{ $t('banners') }}</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/admin/import-products" class="icon-import">
                        <span>{{ $t('import_products') }}</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/admin/import-images" class="icon-images">
                        <span>{{ $t('import_images') }}</span>
                    </router-link>
                </li>
                <li>
                    <!-- "Клієнти" з тією ж версткою, іконка "groups" через emoji або окремий icon-клас -->
                    <router-link to="/admin/clients" class="icon-clients">
                        <span>{{ $t('clients.title') }}</span>
                    </router-link>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <div class="language-switcher">
                <button
                    @click="changeLang('uk')"
                    :class="{ active: locale === 'uk' }"
                >UA</button>
                <button
                    @click="changeLang('en')"
                    :class="{ active: locale === 'en' }"
                >EN</button>
            </div>
            <a href="#" @click.prevent="logout" class="logout-btn">
                <i class="icon-logout"></i>
                <span>{{ $t('logout') }}</span>
            </a>
        </div>
    </div>
</template>
