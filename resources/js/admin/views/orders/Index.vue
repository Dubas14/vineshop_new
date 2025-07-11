<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">{{ $t('orders_list') }}</h1>

        <!-- Блок фільтрів -->
        <div class="flex items-center space-x-4 mb-4">
            <select v-model="dateFilter" class="border rounded p-2">
                <option value="all">{{ $t('all') }}</option>
                <option value="today">{{ $t('today') }}</option>
                <option value="week">{{ $t('this_week') }}</option>
                <option value="month">{{ $t('this_month') }}</option>
                <option value="range">{{ $t('choose_range') }}</option>
            </select>
            <input
                v-if="dateFilter === 'range'"
                type="date"
                v-model="dateRange.from"
                class="border rounded p-2"
            />
            <span v-if="dateFilter === 'range'">—</span>
            <input
                v-if="dateFilter === 'range'"
                type="date"
                v-model="dateRange.to"
                class="border rounded p-2"
            />
        </div>
        <div class="flex items-center space-x-4 mb-4">
            <!-- ... фільтри дат ... -->
            <input
                v-model="search"
                type="text"
                placeholder="Пошук за №, email, телефону"
                class="border rounded p-2 w-64"
            />
        </div>

        <!-- Таблиця замовлень -->
        <table class="min-w-full bg-white shadow rounded">
            <thead>
            <tr class="border-b">
                <th class="text-left p-2">{{ $t('id') }}</th>
                <th class="text-left p-2">{{ $t('name') }}</th>
                <th class="text-left p-2">{{ $t('email') }}</th>
                <th class="text-left p-2">{{ $t('sum') }}</th>
                <th class="text-left p-2">{{ $t('status') }}</th>
                <th class="text-left p-2">{{ $t('created_at') }}</th>
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
                <td class="p-2">{{ formatDate(order.created_at) }}</td>
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
    <div class="flex justify-center my-6" v-if="pagination.last_page > 1">
        <button
            v-for="page in pagination.last_page"
            :key="page"
            :class="[
            'px-3 py-1 mx-1 rounded',
            page === pagination.current_page ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
        ]"
            @click="fetchOrders(page)"
            :disabled="page === pagination.current_page"
        >{{ page }}</button>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

const orders = ref([])
const dateFilter = ref('all')
const dateRange = ref({ from: '', to: '' })
const search = ref('')

const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 20,
    total: 0,
})

const fetchOrders = async (page = 1) => {
    let params = { page }

    if (dateFilter.value === 'today') {
        params.date = 'today'
    } else if (dateFilter.value === 'week') {
        params.date = 'week'
    } else if (dateFilter.value === 'month') {
        params.date = 'month'
    } else if (dateFilter.value === 'range') {
        if (dateRange.value.from) params.from = dateRange.value.from
        if (dateRange.value.to) params.to = dateRange.value.to
    }
    if (search.value) {
        params.search = search.value
    }

    const response = await axios.get('/api/admin/orders', { params })
    orders.value = response.data.data
    pagination.value = {
        current_page: response.data.current_page,
        last_page: response.data.last_page,
        per_page: response.data.per_page,
        total: response.data.total
    }
}

const formatDate = (dt) => {
    if (!dt) return ''
    return new Date(dt).toLocaleString('uk-UA', { dateStyle: 'short', timeStyle: 'short' })
}

onMounted(fetchOrders)

// Автоматичний фільтр при зміні селектора чи діапазону дат
watch(dateFilter, () => {
    if (dateFilter.value === 'range') {
        if (dateRange.value.from && dateRange.value.to) {
            fetchOrders(1)
        }
    } else {
        fetchOrders(1)
    }
})
let searchTimeout
watch(search, () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => fetchOrders(1), 300)
})
watch(dateRange, (val) => {
    if (dateFilter.value === 'range' && val.from && val.to) {
        fetchOrders(1)
    }
}, { deep: true })
</script>
