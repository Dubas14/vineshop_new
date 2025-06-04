

<script setup>
import { useAuthStore } from '@/admin/stores/auth'
import { useRouter } from 'vue-router'
import axios from 'axios'

const auth = useAuthStore()
const router = useRouter()

const logout = async () => {
    await axios.post('/api/admin/logout').catch(() => {})
    auth.logout()
    router.push({ name: 'login' })
}
</script>

<template>
    <div v-if="auth.isAuthenticated" class="sidebar">
        <!-- посилання -->
        <ul>
            <li><router-link to="/admin/dashboard">Dashboard</router-link></li>
            <li><router-link to="/admin/products">Products</router-link></li>
            <li><router-link to="/admin/orders">Orders</router-link></li>
            <li><router-link to="/admin/categories">Categories</router-link></li>
            <li><router-link to="/admin/banners">Banners</router-link></li>
            <li><a href="#" @click.prevent="logout" style="color: red;">Logout</a></li>
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
