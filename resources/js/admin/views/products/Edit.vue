<template>
    <div class="p-6 max-w-4xl mx-auto bg-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">{{ $t('edit') }} {{ $t('products') }}</h1>

        <form @submit.prevent="handleSubmit" class="space-y-6" v-if="loaded">
            <!-- Основні поля -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('name') }}</label>
                    <input v-model="form.name" type="text"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('price') }}</label>
                    <input v-model="form.price" type="number"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('description') }}</label>
                <textarea v-model="form.description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('category') }}</label>
                <select v-model="form.category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="" disabled>{{ $t('choose_type') }}</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!-- Головне зображення -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('current_image') }}</label>
                    <div v-if="form.image" class="flex items-start gap-4">
                        <img :src="`/storage/${form.image}`" class="w-48 h-48 object-contain rounded-lg border border-gray-200">
                        <button type="button" @click="removeMainImage"
                                class="mt-2 px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition">
                            {{ $t('delete') }}
                        </button>
                    </div>
                    <div v-else class="text-gray-500 italic">
                        {{ $t('no_main_image') }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('upload_image') }}</label>
                    <div class="flex items-center gap-4">
                        <label class="cursor-pointer">
                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition inline-block">
                                {{ $t('choose_file') }}
                            </span>
                            <input type="file" @change="handleImageChange" accept="image/*" class="hidden">
                        </label>
                        <span v-if="imageFile" class="text-sm text-gray-500">{{ imageFile.name }}</span>
                    </div>
                    <div v-if="imagePreview" class="mt-2">
                        <img :src="imagePreview" class="max-w-xs border rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Галерея зображень -->
            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('add_gallery') }}</label>

                <div v-if="form.images && form.images.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-4">
                    <div v-for="img in form.images" :key="img.id" class="relative group">
                        <img :src="`/storage/${img.path}`" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                        <button type="button" @click="removeGalleryImage(img.id)"
                                class="absolute top-1 right-1 bg-white bg-opacity-80 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="cursor-pointer">
                        <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition inline-block">
                            {{ $t('choose_files') }}
                        </span>
                        <input type="file" multiple @change="handleGalleryChange" accept="image/*" class="hidden">
                    </label>
                </div>

                <div v-if="galleryPreviews.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-2">
                    <img v-for="(img, i) in galleryPreviews" :key="i" :src="img"
                         class="w-full h-32 object-cover rounded-lg border border-gray-200">
                </div>
            </div>

            <!-- Кнопки -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                <button type="button" @click="router.back()"
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    {{ $t('cancel') }}
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    {{ $t('save_changes') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const route = useRoute()
const router = useRouter()
const form = ref({
    name: '',
    price: 0,
    description: '',
    category_id: '',
    image: null,
    images: []
})
const imageFile = ref(null)
const imagePreview = ref(null)
const categories = ref([])
const loaded = ref(false)
const galleryFiles = ref([])
const galleryPreviews = ref([])

const handleImageChange = (e) => {
    const file = e.target.files[0]
    if (!file) return

    const reader = new FileReader()
    reader.onload = (e) => {
        const img = new Image()
        img.src = e.target.result

        img.onload = () => {
            if (img.width > 600 || img.height > 600) {
                alert(t('image_size_error'))
                return
            }

            imageFile.value = file
            imagePreview.value = e.target.result
        }
    }
    reader.readAsDataURL(file)
}

const handleGalleryChange = (e) => {
    const files = Array.from(e.target.files)
    if (!files.length) return

    galleryFiles.value = []
    galleryPreviews.value = []

    files.forEach(file => {
        const reader = new FileReader()
        reader.onload = (e) => {
            galleryFiles.value.push(file)
            galleryPreviews.value.push(e.target.result)
        }
        reader.readAsDataURL(file)
    })
}

const removeMainImage = async () => {
    if (!confirm(t('delete_image_confirm'))) return
    try {
        await axios.delete(`/api/admin/products/${route.params.id}/image`)
        form.value.image = null
        imageFile.value = null
        imagePreview.value = null
    } catch (error) {
        alert(t('delete_image_error'))
    }
}

const removeGalleryImage = async (id) => {
    if (!confirm(t('delete_image_confirm'))) return
    try {
        await axios.delete(`/api/admin/product-images/${id}`)
        form.value.images = form.value.images.filter(img => img.id !== id)
    } catch (error) {
        alert(t('delete_image_error'))
    }
}

const loadData = async () => {
    try {
        const [productRes, catRes] = await Promise.all([
            axios.get(`/api/admin/products/${route.params.id}`),
            axios.get('/api/admin/categories')
        ])

        form.value = productRes.data
        categories.value = catRes.data
        loaded.value = true
    } catch (error) {
        alert(t('load_data_error'))
        router.back()
    }
}

const handleSubmit = async () => {
    try {
        const formData = new FormData()
        formData.append('_method', 'PUT')
        formData.append('name', form.value.name)
        formData.append('price', form.value.price)
        formData.append('description', form.value.description || '')
        formData.append('category_id', form.value.category_id)

        if (imageFile.value) {
            formData.append('image', imageFile.value)
        } else if (form.value.image === null) {
            formData.append('image_deleted', 'true')
        }

        galleryFiles.value.forEach(file => {
            formData.append('images[]', file)
        })

        await axios.post(
            `/api/admin/products/${route.params.id}`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        )

        alert(t('product_updated'))
        router.push({ name: 'products' })

    } catch (error) {
        console.error('Помилка при збереженні:', error)
        alert(t('product_update_error'))
    }
}

onMounted(loadData)
</script>
