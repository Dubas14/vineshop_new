<template>
    <div class="space-y-8">
        <!-- Головне зображення -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('current_image') }}</label>
            <div v-if="form.image" class="flex items-start gap-4">
                <img :src="`/storage/${form.image}`" class="w-48 h-48 object-contain rounded-lg border" />
                <button type="button" @click="$emit('remove-main')" class="mt-2 px-3 py-1 bg-red-100 text-red-700 rounded-md">
                    {{ $t('delete') }}
                </button>
            </div>
            <div v-else class="text-gray-500 italic">{{ $t('no_main_image') }}</div>

            <label class="block text-sm font-medium text-gray-700 mt-4">{{ $t('upload_image') }}</label>
            <label class="cursor-pointer inline-block px-4 py-2 bg-gray-100 text-gray-700 rounded-md">
                {{ $t('choose_file') }}
                <input type="file" @change="$emit('image-change', $event)" accept="image/*" class="hidden" />
            </label>
            <span v-if="imageFile" class="text-sm text-gray-500 ml-2">{{ imageFile.name }}</span>
            <div v-if="imagePreview" class="mt-2">
                <img :src="imagePreview" class="max-w-xs border rounded-lg" />
            </div>
        </div>

        <!-- Галерея зображень -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('add_gallery') }}</label>

            <div v-if="form.images && form.images.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-4">
                <div v-for="img in form.images" :key="img.id" class="relative group">
                    <img :src="`/storage/${img.path}`" class="w-full h-32 object-cover rounded-lg border" />
                    <button type="button" @click="$emit('remove-gallery', img.id)"
                            class="absolute top-1 right-1 bg-white bg-opacity-80 text-red-600 rounded-full p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <label class="cursor-pointer inline-block px-4 py-2 bg-gray-100 text-gray-700 rounded-md">
                {{ $t('choose_files') }}
                <input type="file" multiple @change="$emit('gallery-change', $event)" accept="image/*" class="hidden" />
            </label>

            <div v-if="galleryPreviews.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-2">
                <img v-for="(img, i) in galleryPreviews" :key="i" :src="img" class="w-full h-32 object-cover rounded-lg border" />
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    form: Object,
    imageFile: Object,
    imagePreview: String,
    galleryPreviews: Array
})
</script>
