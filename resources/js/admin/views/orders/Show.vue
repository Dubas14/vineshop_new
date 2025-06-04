<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Деталі замовлення #{{ order.id }}</h1>

        <div class="mb-6">
            <p><strong>Імʼя:</strong> {{ order.name }}</p>
            <p><strong>Email:</strong> {{ order.email }}</p>
            <p><strong>Сума:</strong> {{ order.total }} грн</p>
            <p><strong>Статус:</strong> {{ order.status }}</p>
        </div>

        <h2 class="text-xl font-semibold mb-2">Товари</h2>
        <table class="min-w-full bg-white shadow rounded">
            <thead>
            <tr class="border-b">
                <th class="p-2 text-left">Назва</th>
                <th class="p-2 text-left">Кількість</th>
                <th class="p-2 text-left">Ціна</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in order.items" :key="item.id" class="border-b">
                <td class="p-2">{{ item.product_name }}</td>
                <td class="p-2">{{ item.quantity }}</td>
                <td class="p-2">{{ item.price }} грн</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const order = ref({ items: [] })

onMounted(async () => {
    const response = await axios.get(`/api/admin/orders/${route.params.id}`)
    order.value = response.data
})
</script>
