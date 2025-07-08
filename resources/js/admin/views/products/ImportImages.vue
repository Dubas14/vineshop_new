<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const toast = useToast()

const files = ref([])
const loading = ref(false)
const result = ref(null)
const error = ref('')
const options = ref({
    setAsMain: false,
    overwrite: false,
})

const handleFileChange = (e) => {
    files.value = Array.from(e.target.files)
    result.value = null
    error.value = ''
}

const submit = async () => {
    if (!files.value.length) {
        error.value = t('import.select_files')
        toast.error(t('import.select_files_for_import'))
        return
    }

    loading.value = true
    error.value = ''
    result.value = null

    try {
        const formData = new FormData()
        files.value.forEach(file => {
            formData.append('images[]', file)
        })

        // Додаємо опції
        formData.append('set_as_main', Number(options.value.setAsMain))
        formData.append('overwrite', Number(options.value.overwrite))

        const response = await axios.post('/api/admin/product-images/import', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })

        result.value = response.data
        toast.success(
            t('import.success', { imported: response.data.stats.imported, total: response.data.stats.total })
        )

    } catch (e) {
        error.value = e?.response?.data?.message || t('import.error_on_import')
        const errorDetails = e?.response?.data?.errors || e.message
        toast.error(t('import.error', { error: error.value }), { timeout: false })
        console.error('Import error:', errorDetails)
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    files.value = []
    result.value = null
    error.value = ''
}
</script>

<template>
    <div class="import-images-page">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">{{ t('import.images_title') }}</h1>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('import.select_images') }}</label>
                    <input
                        type="file"
                        multiple
                        accept="image/jpeg,image/png,image/jpg"
                        @change="handleFileChange"
                        class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100"
                    />
                    <p class="mt-1 text-sm text-gray-500">
                        {{ t('import.file_name_hint') }}
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <div class="flex items-center">
                            <input
                                id="setAsMain"
                                v-model="options.setAsMain"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="setAsMain" class="ml-2 block text-sm text-gray-700">
                                {{ t('import.set_as_main') }}
                            </label>
                        </div>
                        <p class="ml-6 text-xs text-gray-400">{{ t('import.set_as_main_hint') }}</p>
                    </div>
                    <div>
                        <div class="flex items-center">
                            <input
                                id="overwrite"
                                v-model="options.overwrite"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="overwrite" class="ml-2 block text-sm text-gray-700">
                                {{ t('import.overwrite') }}
                            </label>
                        </div>
                        <p class="ml-6 text-xs text-gray-400">{{ t('import.overwrite_hint') }}</p>
                    </div>
                </div>

                <div class="flex space-x-4">
                    <button
                        type="submit"
                        :disabled="loading || !files.length"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="loading">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ t('import.processing') }}
                        </span>
                        <span v-else>{{ t('import.submit') }}</span>
                    </button>

                    <button
                        type="button"
                        @click="resetForm"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        {{ t('import.reset') }}
                    </button>
                </div>

                <div v-if="error" class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                    <p>{{ error }}</p>
                </div>
            </form>
        </div>

        <!-- Results -->
        <div v-if="result" class="space-y-6">
            <!-- Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">{{ t('import.result_title') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-blue-700">{{ t('import.total_files') }}</p>
                        <p class="text-2xl font-bold text-blue-900">{{ result.stats.total }}</p>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-green-700">{{ t('import.successfully_imported') }}</p>
                        <p class="text-2xl font-bold text-green-900">{{ result.stats.imported }}</p>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-yellow-700">{{ t('import.not_found') }}</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ result.stats.not_found }}</p>
                    </div>

                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-red-700">{{ t('import.errors') }}</p>
                        <p class="text-2xl font-bold text-red-900">{{ result.stats.errors }}</p>
                    </div>
                </div>
            </div>

            <!-- Imported -->
            <div v-if="result.imported.length" class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-medium text-gray-900 mb-3 flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ t('import.imported_products') }}
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('import.barcode') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('import.product') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('import.image') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in result.imported" :key="item.barcode">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.barcode }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-medium">#{{ item.product_id }}</span> {{ item.product_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a :href="'/storage/' + item.image_path" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    {{ t('import.view') }}
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Not Found -->
            <div v-if="result.not_found.length" class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-medium text-gray-900 mb-3 flex items-center">
                    <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ t('import.not_found_products') }}
                </h3>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                {{ t('import.not_found_hint') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span v-for="barcode in result.not_found" :key="barcode" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        {{ barcode }}
                    </span>
                </div>
            </div>

            <!-- Errors -->
            <div v-if="result.errors.length" class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-medium text-gray-900 mb-3 flex items-center">
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ t('import.processing_errors') }}
                </h3>

                <div class="space-y-2">
                    <div v-for="(error, index) in result.errors" :key="index" class="p-3 bg-red-50 rounded-md">
                        <p class="text-sm text-red-700">{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.import-images-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

table {
    font-size: 0.875rem;
}

table th, table td {
    padding: 0.75rem 1rem;
}
</style>
