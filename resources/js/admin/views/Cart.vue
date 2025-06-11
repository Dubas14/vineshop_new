<template>
    <div class="p-4 max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">{{ $t('your_cart') }}</h1>

        <div v-if="Object.keys(cart).length">
            <table class="min-w-full bg-white shadow rounded">
                <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">{{ $t('name') }}</th>
                    <th class="p-2 text-left">{{ $t('price') }}</th>
                    <th class="p-2 text-left">{{ $t('quantity') }}</th>
                    <th class="p-2 text-left">{{ $t('sum') }}</th>
                    <th class="p-2 text-left">{{ $t('actions') }}</th>
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
                            {{ $t('remove') }}
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="mt-4 font-semibold">
                {{ $t('total_amount') }}: {{ formatPrice(total) }}
            </div>


        </div>

        <div v-else>
            <p>{{ $t('cart_empty') }}</p>
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

const removeItem = async (id) => {
    await axios.delete('/api/cart/remove/' + id)
    delete cart.value[id]
}

const total = computed(() => {
    return Object.values(cart.value).reduce((sum, item) => {
        return sum + item.price * item.quantity
    }, 0)
})

const formatPrice = (value) => {
    return value.toFixed(2) + ' грн'
}



onMounted(fetchCart)
</script>

<style scoped>
input[type="number"]::-webkit-inner-spin-button {
    opacity: 1;
}
</style>
