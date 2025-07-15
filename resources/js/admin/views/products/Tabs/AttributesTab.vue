<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-for="field in fields" :key="field.key">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t(field.label) }}</label>
            <input
                v-model="form[field.key]"
                :type="field.type"
                class="w-full px-4 py-2 border border-gray-300 rounded-md"
            />
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    form: Object
})

const fields = [
    { key: 'brand', label: 'brand', type: 'text' },
    { key: 'manufacturer', label: 'manufacturer', type: 'text' },
    { key: 'country', label: 'country', type: 'text' },
    { key: 'region', label: 'region', type: 'text' },
    { key: 'classification', label: 'classification', type: 'text' },
    { key: 'type', label: 'type', type: 'text' },
    { key: 'package_type', label: 'package_type', type: 'text' },
    { key: 'color', label: 'color', type: 'text' },
    { key: 'sugar_content', label: 'sugar_content', type: 'text' },
    { key: 'volume', label: 'volume', type: 'number' },
    { key: 'sort', label: 'sort', type: 'text' },
    { key: 'taste', label: 'taste', type: 'text' },
    { key: 'aroma', label: 'aroma', type: 'text' },
    { key: 'pairing', label: 'pairing', type: 'text' },
    { key: 'old_price', label: 'old_price', type: 'number' }
]
</script>
