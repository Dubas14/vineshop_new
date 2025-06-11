<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">{{ $t('order_details') }} #{{ order.id }}</h1>

        <div class="mb-6 space-y-1">
            <p><strong>{{ $t('name') }}:</strong> {{ order.name }}</p>
            <p><strong>{{ $t('email') }}:</strong> {{ order.email }}</p>
            <p><strong>{{ $t('sum') }}:</strong> {{ totalSum }} грн</p>
            <p>
                <strong>{{ $t('status') }}:</strong>
                <select v-model="order.status" class="border rounded px-2 py-1 ml-2">
                    <option value="Очікує підтвердження">{{ $t('pending') }}</option>
                    <option value="Підтверджено">{{ $t('completed') }}</option>
                    <option value="Скасовано">{{ $t('cancelled') }}</option>
                </select>
            </p>
        </div>

        <button
            @click="updateOrder"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-6"
        >
            {{ $t('save_changes') }}
        </button>

        <h2 class="text-xl font-semibold mb-2">{{ $t('items') }}</h2>
        <table class="min-w-full bg-white shadow rounded">
            <thead>
            <tr class="border-b bg-gray-100">
                <th class="p-2 text-left">{{ $t('name') }}</th>
                <th class="p-2 text-left">{{ $t('quantity') }}</th>
                <th class="p-2 text-left">{{ $t('price') }}</th>
                <th class="p-2 text-left">{{ $t('discount') }}</th>
                <th class="p-2 text-left">{{ $t('sum') }}</th>
                <th class="p-2 text-left">{{ $t('actions') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in order.items" :key="item.id" class="border-b">
                <td class="p-2">{{ item.product?.name || '—' }}</td>
                <td class="p-2">
                    <input type="number" v-model.number="item.quantity" min="1" class="w-16 border rounded px-2">
                </td>
                <td class="p-2">
                    <input type="number" v-model.number="item.price" step="0.01" class="w-24 border rounded px-2">
                </td>
                <td class="p-2">
                    <input type="number" v-model.number="item.discount" min="0" max="100" class="w-16 border rounded px-2">
                </td>
                <td class="p-2">
                    {{ itemTotal(item) }} грн
                </td>
                <td class="p-2">
                    <button @click="removeItem(index)" class="text-red-600 hover:underline">{{ $t('remove') }}</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const route = useRoute()
const router = useRouter()

const order = ref({ items: [] })

const fetchOrder = async () => {
    const response = await axios.get(`/api/admin/orders/${route.params.id}`)
    response.data.items.forEach(i => { if (!i.discount) i.discount = 0 })
    order.value = response.data
}

const itemTotal = item => {
    const discounted = item.price * (1 - (item.discount || 0) / 100)
    return (discounted * item.quantity).toFixed(2)
}

const totalSum = computed(() => {
    return order.value.items.reduce((sum, item) => {
        const price = item.price * (1 - (item.discount || 0) / 100)
        return sum + price * item.quantity
    }, 0).toFixed(2)
})

const updateOrder = async () => {
    await axios.put(`/api/admin/orders/${route.params.id}`, {
        status: order.value.status,
        items: order.value.items,
    })
    alert(t('save_success'))
    router.push('/admin/orders')
}

const removeItem = index => {
    order.value.items.splice(index, 1)
}

onMounted(fetchOrder)
</script>
