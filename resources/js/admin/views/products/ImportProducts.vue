<template>
    <div>
        <h2 class="text-xl font-bold mb-4">Імпорт товарів з Excel</h2>
        <input type="file" @change="onFileChange" accept=".xls,.xlsx" />

        <div v-if="columns.length" class="mt-4">
            <table class="min-w-full table-auto border">
                <thead>
                <tr>
                    <th v-for="(col, idx) in columns" :key="idx" class="p-1 border-b">
                        <select v-model="mapping[idx]" class="border rounded px-2 py-1">
                            <option value="">-- Не імпортувати --</option>
                            <option v-for="opt in fieldOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </th>
                </tr>
                <tr>
                    <th v-for="col in columns" :key="col" class="p-1 border-b">{{ col }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, rowIdx) in previewRows" :key="rowIdx">
                    <td v-for="(col, colIdx) in columns" :key="colIdx" class="p-1">{{ row[colIdx] }}</td>
                </tr>
                </tbody>
            </table>
            <button @click="importProducts" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Імпортувати</button>
        </div>

        <div v-if="importResult" class="mt-2 text-green-700">{{ importResult }}</div>
        <div v-if="importError" class="mt-2 text-red-700">{{ importError }}</div>
        <div v-if="importErrors && importErrors.length">
            <ul>
                <li v-for="err in importErrors" :key="err" class="text-red-700">{{ err }}</li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const columns = ref([])
const previewRows = ref([])
const mapping = ref([])

const file = ref(null)
const importResult = ref('')
const importError = ref('')

const fieldOptions = [
    { value: 'barcode', label: 'Штрихкод товару' },
    { value: 'supplier_code', label: 'Код постачальника' },
    { value: 'name', label: 'Найменування товару' },
    { value: 'country', label: 'Країна' },
    { value: 'manufacturer', label: 'Виробник' },
    { value: 'brand', label: 'Торгова марка' },
    { value: 'purchase_price', label: 'Ціна вхідна' },
    { value: 'sale_price', label: 'Ціна продажна' },
    { value: 'quantity', label: 'Кількість' },
    { value: 'multiplicity', label: 'Кратність' },
    // Додавай нові поля тут
]

async function onFileChange(e) {
    file.value = e.target.files[0]
    if (!file.value) return
    const formData = new FormData()
    formData.append('file', file.value)
    try {
        const resp = await axios.post('/api/import/preview', formData)
        columns.value = resp.data.columns
        previewRows.value = resp.data.rows
        mapping.value = Array(columns.value.length).fill('')
        importError.value = ''
    } catch (e) {
        importError.value = e.response?.data?.message || 'Помилка при читанні файлу'
        columns.value = []
        previewRows.value = []
    }
}

const importErrors = ref([])
async function importProducts() {
    if (!file.value) return
    importResult.value = ''
    importError.value = ''
    const formData = new FormData()
    formData.append('file', file.value)
    formData.append('mapping', JSON.stringify(mapping.value))
    try {
        const resp = await axios.post('/api/import/products', formData)
        importResult.value = 'Імпортовано: ' + resp.data.count + ' товарів'
        importErrors.value = resp.data.errors || []
    } catch (e) {
        importError.value = e.response?.data?.message || 'Помилка імпорту'
    }
}

</script>
