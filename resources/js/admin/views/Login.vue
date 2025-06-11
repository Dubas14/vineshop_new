<template>
    <div class="login-page">
        <h1>{{ $t('admin_login') }}</h1>
        <form @submit.prevent="handleLogin">
            <input v-model="email" type="email" :placeholder="$t('email')" required />
            <input v-model="password" type="password" :placeholder="$t('password')" required />
            <button type="submit">{{ $t('login') }}</button>
            <p v-if="error" class="error">{{ error }}</p>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '@/admin/stores/auth'

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()
const auth = useAuthStore()
const { t } = useI18n()

const handleLogin = async () => {
    try {
        await auth.login(email.value, password.value)
        error.value = ''
        await router.push({ name: 'dashboard' })
    } catch (err) {
        console.error(err)
        error.value = t('login_error')
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
