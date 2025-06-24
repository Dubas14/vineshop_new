<template>
    <div class="container mx-auto px-4 py-8">
        <!-- Заголовок та опис -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $t('categories_page_title') }}</h1>
            <p class="text-gray-600">{{ $t('categories_page_description') }}</p>
        </div>

        <!-- Панель дій -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <router-link
                to="/admin/categories/create"
                class="btn-primary flex items-center gap-2"
            >
                <PlusIcon class="w-5 h-5" />
                {{ $t('add_category') }}
            </router-link>

            <div class="relative w-full sm:w-64">
                <input
                    v-model="searchQuery"
                    type="text"
                    :placeholder="$t('search_placeholder')"
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
        </div>

        <!-- Інформація про результати пошуку -->
        <div v-if="searchQuery && !loading" class="mb-4 text-sm text-gray-600">
            {{ $t('search_results', { count: filteredCategories.length, query: searchQuery }) }}
        </div>

        <!-- Лоадер -->
        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>

        <!-- Таблиця категорій -->
        <div v-else-if="filteredCategories.length" class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $t('name') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $t('slug') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $t('created_at') }}
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $t('actions') }}
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr
                    v-for="category in filteredCategories"
                    :key="category.id"
                    class="hover:bg-gray-50 transition-colors"
                >
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-blue-100 flex items-center justify-center">
                                <FolderIcon class="h-5 w-5 text-blue-600" />
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900">{{ category.name }}</div>
                                <div class="text-sm text-gray-500">ID: {{ category.id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                {{ category.slug }}
              </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formatDate(category.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-3">
                            <router-link
                                :to="`/admin/categories/${category.id}/edit`"
                                class="btn-icon text-blue-600 hover:bg-blue-100"
                                :title="$t('edit')"
                            >
                                <PencilIcon class="h-4 w-4" />
                            </router-link>
                            <button
                                @click="deleteCategory(category.id)"
                                class="btn-icon text-red-600 hover:bg-red-100"
                                :title="$t('delete')"
                            >
                                <TrashIcon class="h-4 w-4" />
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Пустий стан -->
        <div v-else class="text-center py-16 bg-white rounded-xl shadow">
            <FolderOpenIcon class="mx-auto h-16 w-16 text-gray-400" />

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
                <h3 class="mt-5 text-lg font-medium text-gray-900">{{ $t('no_categories_title') }}</h3>
                <p class="mt-2 text-sm text-gray-500">{{ $t('no_categories_description') }}</p>
                <div class="mt-6">
                    <router-link
                        to="/admin/categories/create"
                        class="btn-primary inline-flex items-center gap-2"
                    >
                        <PlusIcon class="w-5 h-5" />
                        {{ $t('add_first_category') }}
                    </router-link>
                </div>
            </template>
        </div>

        <!-- Тостові сповіщення -->
        <ToastNotification
            v-if="showToast"
            :type="toastType"
            :message="toastMessage"
            @close="showToast = false"
        />
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
    FolderIcon,
    FolderOpenIcon,
    MagnifyingGlassIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline'
import ToastNotification from "@/admin/components/ToastNotification.vue";

const { t } = useI18n()
const categories = ref([])
const loading = ref(true)
const searchQuery = ref('')
const showToast = ref(false)
const toastMessage = ref('')
const toastType = ref('success')

// Отримання категорій
const fetchCategories = async () => {
    try {
        loading.value = true
        const response = await axios.get('/api/admin/categories')
        categories.value = response.data
    } catch (error) {
        showNotification(t('fetch_error'), 'error')
    } finally {
        loading.value = false
    }
}

// Видалення категорії
const deleteCategory = async (id) => {
    if (!confirm(t('delete_category_confirm'))) return

    try {
        await axios.delete(`/api/admin/categories/${id}`)
        await fetchCategories()
        showNotification(t('category_deleted'), 'success')
    } catch (error) {
        showNotification(t('delete_error'), 'error')
    }
}

// Функція нормалізації для пошуку
const normalizeString = (str) => {
    if (typeof str !== 'string') return ''
    return str
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/\s+/g, ' ')
        .trim();
}

// Фільтрація категорій з підтримкою кирилиці
const filteredCategories = computed(() => {
    if (!searchQuery.value) return categories.value

    const query = normalizeString(searchQuery.value)
    if (!query) return categories.value

    return categories.value.filter(c => {
        const name = c.name ? normalizeString(c.name) : ''
        const slug = c.slug ? normalizeString(c.slug) : ''
        return name.includes(query) || slug.includes(query)
    })
})

// Форматування дати
const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' }
    return new Date(dateString).toLocaleDateString(undefined, options)
}

// Показ сповіщень
const showNotification = (message, type) => {
    toastMessage.value = message
    toastType.value = type
    showToast.value = true
    setTimeout(() => showToast.value = false, 3000)
}

onMounted(fetchCategories)
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
