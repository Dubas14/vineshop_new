<template>
    <div class="p-4 max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Ваш кошик</h1>

        <div v-if="Object.keys(cart).length">
            <table class="min-w-full bg-white shadow rounded">
                <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Назва</th>
                    <th class="p-2 text-left">Ціна</th>
                    <th class="p-2 text-left">Кількість</th>
                    <th class="p-2 text-left">Сума</th>
                    <th class="p-2 text-left">Дії</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, id) in cart" :key="id" class="border-b">
                    <td class="p-2">{{ item.name }}</td>
                    <td class="p-2">{{ formatPrice(item.price) }}</td>
                    <td class="p-2">
                        <input type="number"
                               v-model.number="item.quantity"
                               @change="updateQuantity(id, item.quantity)"
                               min="1"
                               class="w-16 border rounded px-2 py-1 text-center">
                    </td>
                    <td class="p-2">{{ formatPrice(item.price * item.quantity) }}</td>
                    <td class="p-2">
                        <button @click="removeItem(id)" class="text-red-600 hover:underline">
                            Видалити
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="mt-4 font-semibold">
                Загальна сума: {{ formatPrice(total) }}
            </div>

            <form @submit.prevent="submitOrder" class="mt-8 space-y-4 bg-gray-100 p-6 rounded shadow max-w-md">
                <h2 class="text-xl font-semibold mb-4">Оформлення замовлення</h2>

                <div>
                    <label for="name" class="block font-medium mb-1">Ваше імʼя</label>
                    <input v-model="order.name" id="name" required class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label for="phone" class="block font-medium mb-1">Телефон</label>
                    <input v-model="order.phone" id="phone" required class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label for="email" class="block font-medium mb-1">Email</label>
                    <input v-model="order.email" id="email" type="email" class="w-full border px-3 py-2 rounded">
                </div>

                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                    Оформити замовлення
                </button>
            </form>
        </div>

        <div v-else>
            <p>Кошик порожній.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const cart = ref({})
const router = useRouter()

const order = ref({
    name: '',
    phone: '',
    email: ''
})

const fetchCart = async () => {
    const response = await axios.get('/api/cart')
    cart.value = response.data
}

const updateQuantity = async (id, quantity) => {
    quantity = Math.max(1, quantity)
    cart.value[id].quantity = quantity
    await axios.put(`/api/cart/update/${id}`, { quantity })
}

const removeItem = (id) => {
    delete cart.value[id]
    // todo: API remove
}

const total = computed(() => {
    return Object.values(cart.value).reduce((sum, item) => {
        return sum + item.price * item.quantity
    }, 0)
})

const formatPrice = (value) => {
    return value.toFixed(2) + ' грн'
}

const submitOrder = async () => {
    try {
        await axios.post('/order', {
            name: order.value.name,
            phone: order.value.phone,
            email: order.value.email
        })
        alert('Замовлення оформлено!')
        router.push('/')
    } catch (e) {
        console.error(e)
        alert('Помилка при оформленні замовлення.')
    }
}

onMounted(fetchCart)
</script>

<style scoped>
input[type="number"]::-webkit-inner-spin-button {
    opacity: 1;
}
</style>
