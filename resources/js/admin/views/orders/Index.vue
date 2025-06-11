<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">{{ $t('orders_list') }}</h1>

        <table class="min-w-full bg-white shadow rounded">
            <thead>
            <tr class="border-b">
                <th class="text-left p-2">{{ $t('id') }}</th>
                <th class="text-left p-2">{{ $t('name') }}</th>
                <th class="text-left p-2">{{ $t('email') }}</th>
                <th class="text-left p-2">{{ $t('sum') }}</th>
                <th class="text-left p-2">{{ $t('status') }}</th>
                <th class="text-left p-2">{{ $t('actions') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="order in orders"
                :key="order.id"
                class="border-b hover:bg-gray-100"
            >
                <td class="p-2">{{ order.id }}</td>
                <td class="p-2">{{ order.name }}</td>
                <td class="p-2">{{ order.email }}</td>
                <td class="p-2">{{ order.total_price }} грн</td>
                <td class="p-2">{{ order.status }}</td>
                <td class="p-2 space-x-2">
                    <router-link
                        :to="`/admin/orders/${order.id}`"
                        class="text-blue-600 hover:underline"
                    >
                        {{ $t('view') }}
                    </router-link>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const orders = ref([])

const fetchOrders = async () => {
    const response = await axios.get('/api/admin/orders')
    orders.value = response.data.data ?? response.data;
}

onMounted(fetchOrders)
</script>
