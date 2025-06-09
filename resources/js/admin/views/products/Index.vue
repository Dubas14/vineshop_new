<template>
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Товари</h1>
            <router-link to="/admin/products/create" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Додати товар
            </router-link>
        </div>

        <table class="min-w-full bg-white shadow rounded">
            <thead>
            <tr class="border-b">
                <th class="text-left p-2">Назва</th>
                <th class="text-left p-2">Ціна</th>
                <th class="text-left p-2">Дії</th>
                <th class="text-left p-2">Фото</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="product in products" :key="product.id" class="border-b hover:bg-gray-100">
                <td class="p-2">{{ product.name }}</td>
                <td class="p-2">{{ product.price }} грн</td>
                <td class="p-2 space-x-2">
                    <router-link
                        :to="`/admin/products/${product.id}/edit`"
                        class="text-blue-600 hover:underline"
                    >
                        Редагувати
                    </router-link>
                    <button @click="deleteProduct(product.id)" class="text-red-600 hover:underline">
                        Видалити
                    </button>
                </td>
                <td class="p-2">
                    <img
                        v-if="product.images && product.images.length"
                        :src="`/storage/${product.images[0].path}`"
                        class="w-20 h-20 object-cover rounded shadow"
                        alt="Зображення товару"
                    >
                    <span v-else-if="product.image" >
                        <img :src="`/storage/${product.image}`" class="w-20 h-20 object-cover rounded shadow" alt="Фото">
                    </span>
                    <span v-else class="text-gray-400 italic">немає</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const products = ref([])

const fetchProducts = async () => {
    const response = await axios.get('/api/admin/products')
    products.value = response.data.data ?? response.data
}

const deleteProduct = async (id) => {
    if (confirm('Ви впевнені, що хочете видалити цей товар?')) {
        await axios.delete(`/api/admin/products/${id}`)
        await fetchProducts()
    }
}

onMounted(fetchProducts)
</script>
