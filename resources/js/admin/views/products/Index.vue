<template>
    <div class="container mx-auto px-4 py-8">
        <!-- Заголовок та кнопка додати -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $t('product_list') }}</h1>
            </div>
            <router-link
                to="/admin/products/create"
                class="btn-primary flex items-center gap-2"
            >
                <PlusIcon class="w-5 h-5" />
                {{ $t('add_product') }}
            </router-link>
        </div>

        <!-- Панель пошуку -->
        <div class="relative w-full sm:w-80 mb-6">
            <input
                v-model="searchQuery"
                type="text"
                :placeholder="$t('search_products_placeholder')"
                class="w-full pl-10 pr-10 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition"
            />
            <MagnifyingGlassIcon class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" />
            <button
                v-if="searchQuery"
                @click="searchQuery = ''"
                class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600"
            >
                <XMarkIcon class="w-5 h-5" />
            </button>
        </div>
        <!-- Інформація про результати пошуку -->
        <div v-if="searchQuery && !loading" class="mb-4 text-sm text-gray-600">
            {{ $t('search_results', { count: filteredProducts.length, query: searchQuery }) }}
        </div>

        <!-- Лоадер -->
        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>

        <!-- Таблиця товарів -->
        <div v-else-if="filteredProducts.length" class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $t('name') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $t('price') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        SKU
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $t('photo') }}
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $t('actions') }}
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr
                    v-for="product in filteredProducts"
                    :key="product.id"
                    class="hover:bg-gray-50 transition-colors"
                >
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ product.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ product.price }} грн</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ product.sku || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img
                            :src="getProductImage(product)"
                            :alt="product.name"
                            class="w-16 h-16 object-cover rounded shadow border"
                        >
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-3">
                            <router-link
                                :to="`/admin/products/${product.id}/edit`"
                                class="btn-icon text-blue-600 hover:bg-blue-100"
                                :title="$t('edit')"
                            >
                                <PencilIcon class="h-5 w-5" />
                            </router-link>
                            <button
                                @click="deleteProduct(product.id)"
                                class="btn-icon text-red-600 hover:bg-red-100"
                                :title="$t('delete')"
                            >
                                <TrashIcon class="h-5 w-5" />
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- ПАГІНАЦІЯ -->
        <div class="flex justify-center my-6" v-if="pagination.last_page > 1">
            <button
                v-for="page in pagination.last_page"
                :key="page"
                :class="[
            'px-3 py-1 mx-1 rounded',
            page === pagination.current_page ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
        ]"
                @click="fetchProducts(page)"
                :disabled="page === pagination.current_page"
            >{{ page }}</button>
        </div>

        <!-- Пустий стан -->
        <div v-else class="text-center py-16 bg-white rounded-xl shadow">
            <template v-if="searchQuery">
                <h3 class="mt-5 text-lg font-medium text-gray-900">{{ $t('no_search_results_title') }}</h3>
                <p class="mt-2 text-sm text-gray-500">
                    {{ $t('no_search_results_description', { query: searchQuery }) }}
                </p>
                <button
                    @click="searchQuery = ''"
                    class="mt-6 btn-secondary inline-flex items-center gap-2"
                >
                    <XMarkIcon class="w-5 h-5" />
                    {{ $t('clear_search') }}
                </button>
            </template>
            <template v-else>
                <h3 class="mt-5 text-lg font-medium text-gray-900">{{ $t('no_products_title') }}</h3>
                <p class="mt-2 text-sm text-gray-500">{{ $t('no_products_description') }}</p>
                <div class="mt-6">
                    <router-link
                        to="/admin/products/create"
                        class="btn-primary inline-flex items-center gap-2"
                    >
                        <PlusIcon class="w-5 h-5" />
                        {{ $t('add_first_product') }}
                    </router-link>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useI18n } from 'vue-i18n'
import {
    PlusIcon,
    PencilIcon,
    TrashIcon,
    MagnifyingGlassIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline'

const { t } = useI18n()

const products = ref([])
const loading = ref(true)
const searchQuery = ref('')
const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 20,
    total: 0,
})

// Головна зміна: fetchProducts приймає page (номер сторінки)
const fetchProducts = async (page = 1) => {
    try {
        loading.value = true
        const response = await axios.get(`/api/admin/products?page=${page}`)
        products.value = response.data.data
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            per_page: response.data.per_page,
            total: response.data.total
        }
    } finally {
        loading.value = false
    }
}

// Видалення товару
const deleteProduct = async (id) => {
    if (confirm(t('delete_product_confirm'))) {
        await axios.delete(`/api/admin/products/${id}`)
        await fetchProducts(pagination.value.current_page)
    }
}

// Фільтрація по пошуку (фронт)
const normalizeString = (str) => {
    if (typeof str !== 'string') return ''
    return str
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/\s+/g, ' ')
        .trim()
}
const filteredProducts = computed(() => {
    if (!searchQuery.value) return products.value

    const query = normalizeString(searchQuery.value)
    if (!query) return products.value

    return products.value.filter(p => {
        const name = p.name ? normalizeString(p.name) : ''
        const sku = p.sku ? normalizeString(p.sku) : ''
        const slug = p.slug ? normalizeString(p.slug) : ''
        return name.includes(query) || sku.includes(query) || slug.includes(query)
    })
})

// Головна картинка товару
const getProductImage = (product) => {
    if (product.images && product.images.length) {
        return `/storage/${product.images[0].path}`
    } else if (product.image) {
        return `/storage/${product.image}`
    } else {
        return '/storage/no-image.png'
    }
}

onMounted(() => fetchProducts())
</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg
    hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2
    focus:ring-blue-500 transition-colors shadow-sm;
}
.btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg
    hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2
    focus:ring-gray-400 transition-colors shadow-sm;
}
.btn-icon {
    @apply p-2 rounded-full transition-colors;
}
.container {
    max-width: 1200px;
}
</style>
