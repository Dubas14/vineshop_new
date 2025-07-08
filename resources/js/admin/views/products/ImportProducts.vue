<template>
    <div class="max-w-6xl mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">{{ t('import_csv.title') }}</h2>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('import_csv.choose_file') }}</label>
                <input
                    type="file"
                    @change="onFileChange"
                    accept=".csv,.txt"
                    class="block w-full text-sm text-gray-500
          file:mr-4 file:py-2 file:px-4
          file:rounded-md file:border-0
          file:text-sm file:font-semibold
          file:bg-blue-50 file:text-blue-700
          hover:file:bg-blue-100"
                />
            </div>

            <div v-if="isLoading" class="text-center py-4">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
                <p class="mt-2 text-gray-600">{{ t('import_csv.processing') }}</p>
            </div>

            <div v-if="previewData" class="mt-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ t('import_csv.settings') }}</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-200">
                        <thead>
                        <tr class="bg-gray-50">
                            <th
                                v-for="(col, idx) in previewData.columns"
                                :key="idx"
                                class="p-3 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                <select
                                    v-model="mapping[idx]"
                                    class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                                    <option value="">{{ t('import_csv.dont_import') }}</option>
                                    <option
                                        v-for="opt in fieldOptions"
                                        :key="opt.value"
                                        :value="opt.value"
                                        :disabled="isFieldMapped(opt.value) && mapping[idx] !== opt.value"
                                    >
                                        {{ opt.label }}
                                    </option>
                                </select>
                            </th>
                        </tr>
                        <tr class="bg-gray-100">
                            <th
                                v-for="(col, idx) in previewData.columns"
                                :key="idx"
                                class="p-3 border border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider"
                            >
                                {{ col }}
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(row, rowIdx) in previewData.rows" :key="rowIdx">
                            <td
                                v-for="(cell, cellIdx) in row"
                                :key="cellIdx"
                                class="p-3 border border-gray-300 text-sm text-gray-700"
                            >
                                {{ cell }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        @click="importProducts"
                        :disabled="isImporting || !hasRequiredFields"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="isImporting">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ t('import_csv.importing') }}
                        </span>
                        <span v-else>{{ t('import_csv.start_import') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Результати імпорту -->
        <div v-if="importResult" class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ t('import_csv.result_title') }}</h3>

            <div v-if="importResult.success" class="mb-4">
                <div class="p-4 bg-green-50 border-l-4 border-green-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ t('import_csv.import_success') }}
                                <span v-if="importResult.count">({{ t('import_csv.imported', { count: importResult.count }) }})</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="importResult.error" class="mb-4">
                <div class="p-4 bg-red-50 border-l-4 border-red-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ importResult.error }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="importResult.errors?.length" class="mt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">{{ t('import_csv.errors_title') }}</h4>
                <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                    <li v-for="(error, idx) in importResult.errors" :key="idx" class="p-3 text-sm text-red-600">
                        {{ error }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useI18n } from 'vue-i18n'

const file = ref(null)
const isLoading = ref(false)
const isImporting = ref(false)
const previewData = ref(null)
const mapping = ref([])
const importResult = ref(null)
const { t } = useI18n()

const fieldOptions = [
    { value: 'barcode', label: t('import_csv.required_barcode') },
    { value: 'name', label: t('import_csv.required_name') },
    { value: 'supplier_code', label: t('import_csv.supplier_code') },
    { value: 'country', label: t('import_csv.country') },
    { value: 'manufacturer', label: t('import_csv.manufacturer') },
    { value: 'brand', label: t('import_csv.brand') },
    { value: 'purchase_price', label: t('import_csv.purchase_price') },
    { value: 'sale_price', label: t('import_csv.sale_price') },
    { value: 'quantity', label: t('import_csv.quantity') },
    { value: 'multiplicity', label: t('import_csv.multiplicity') },
    { value: 'category_id', label: t('import_csv.category_id') },
]

const hasRequiredFields = computed(() => {
    return mapping.value.includes('barcode') && mapping.value.includes('name')
})

function isFieldMapped(field) {
    return mapping.value.includes(field)
}

async function onFileChange(e) {
    file.value = e.target.files[0]
    if (!file.value) return

    isLoading.value = true
    importResult.value = null
    previewData.value = null

    const formData = new FormData()
    formData.append('file', file.value)

    try {
        const response = await axios.post('/api/products/import/preview', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        previewData.value = {
            columns: response.data.columns,
            rows: response.data.rows
        }
        mapping.value = Array(previewData.value.columns.length).fill('')
    } catch (error) {
        importResult.value = {
            success: false,
            error: error.response?.data?.message || 'Помилка при читанні файлу'
        }
    } finally {
        isLoading.value = false
    }
}

async function importProducts() {
    if (!file.value || !hasRequiredFields.value) return

    isImporting.value = true
    importResult.value = null

    const formData = new FormData()
    formData.append('file', file.value)
    formData.append('mapping', JSON.stringify(mapping.value))

    try {
        const response = await axios.post('/api/products/import/process', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        importResult.value = {
            success: true,
            message: 'Імпорт успішно завершено',
            count: response.data.count,
            errors: response.data.errors || []
        }
    } catch (error) {
        importResult.value = {
            success: false,
            error: error.response?.data?.message || 'Помилка при імпорті',
            errors: error.response?.data?.errors || []
        }
    } finally {
        isImporting.value = false
    }
}
</script>
