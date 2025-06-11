<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">{{ $t('order_edit') }}</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">{{ $t('status') }}</label>
                <select v-model="order.status" class="w-full border px-3 py-2 rounded">
                    <option value="pending">{{ $t('pending') }}</option>
                    <option value="processing">{{ $t('processing') }}</option>
                    <option value="completed">{{ $t('completed') }}</option>
                    <option value="cancelled">{{ $t('cancelled') }}</option>
                </select>
            </div>

            <div class="space-y-2">
                <h2 class="font-semibold text-lg">{{ $t('items') }}</h2>
                <div v-for="(item, index) in order.items" :key="item.id" class="grid grid-cols-6 gap-2 items-center">
                    <div class="col-span-2">{{ item.name }}</div>
                    <input type="number" v-model.number="item.price" class="border px-2 py-1 rounded col-span-1" :placeholder="$t('price')">
                    <input type="number" v-model.number="item.quantity" class="border px-2 py-1 rounded col-span-1" :placeholder="$t('quantity')">
                    <div class="col-span-1 font-bold">
                        {{ (item.price * item.quantity).toFixed(2) }} грн
                    </div>
                    <button @click.prevent="removeItem(index)" class="text-red-600">{{ $t('remove') }}</button>
                </div>
                <button @click.prevent="addItem" class="text-blue-600 mt-2">+ {{ $t('add_item') }}</button>
            </div>

            <div class="font-bold text-lg">{{ $t('total_sum') }}: {{ totalSum }} грн</div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                {{ $t('save_changes') }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const order = ref({ status: 'pending', items: [] })

const totalSum = computed(() => {
    return order.value.items.reduce((sum, item) => sum + item.price * item.quantity, 0).toFixed(2)
})

const fetchOrder = async () => {
    const { data } = await axios.get(`/api/admin/orders/${route.params.id}`)
    order.value = data
}

const submit = async () => {
    await axios.put(`/api/admin/orders/${route.params.id}`, order.value)
    router.push({ name: 'admin.orders' })
}

const removeItem = (index) => {
    order.value.items.splice(index, 1)
}

const addItem = () => {
    order.value.items.push({ name: '', price: 0, quantity: 1 })
}

onMounted(fetchOrder)
</script>
