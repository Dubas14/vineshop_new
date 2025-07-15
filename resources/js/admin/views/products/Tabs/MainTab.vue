<template>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('name') }}</label>
                <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('price') }}</label>
                <input v-model="form.price" type="number" class="w-full px-4 py-2 border border-gray-300 rounded-md" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('description') }}</label>
            <textarea v-model="form.description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('category') }}</label>
            <select v-model="form.category_id" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                <option value="" disabled>{{ $t('choose_type') }}</option>
                <option v-for="cat in categoryOptions" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    form: Object,
    categoryOptions: Array
})
</script>
