<template>
    <div class="login-page">
        <h1>Admin Login</h1>
        <form @submit.prevent="handleLogin">
            <input v-model="email" type="email" placeholder="Email" required />
            <input v-model="password" type="password" placeholder="Password" required />
            <button type="submit">Login</button>
            <p v-if="error" class="error">{{ error }}</p>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/admin/stores/auth'

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()
const auth = useAuthStore()

const handleLogin = async () => {
    try {
        await auth.login(email.value, password.value)
        error.value = ''
        await router.push({ name: 'dashboard' })
    } catch (err) {
        console.error(err)
        error.value = 'Невірні дані для входу'
    }
}
</script>

<style scoped>
.login-page {
    max-width: 300px;
    margin: 40px auto;
}
input,
button {
    display: block;
    width: 100%;
    margin-bottom: 10px;
    padding: 8px;
}
.error {
    color: red;
    margin-top: 10px;
}
</style>
