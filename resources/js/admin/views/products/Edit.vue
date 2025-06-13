<template>
    <div class="p-4 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ $t('edit') }} {{ $t('products') }}</h1>

        <form @submit.prevent="handleSubmit" class="space-y-4" v-if="loaded">
            <div>
                <label class="block mb-1 font-medium">{{ $t('name') }}</label>
                <input v-model="form.name" type="text" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">{{ $t('price') }}</label>
                <input v-model="form.price" type="number" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">{{ $t('description') }}</label>
                <textarea v-model="form.description" class="w-full border px-3 py-2 rounded"></textarea>
            </div>

            <div>
                <label class="block mb-1 font-medium">{{ $t('category') }}</label>
                <select v-model="form.category_id" class="w-full border px-3 py-2 rounded" required>
                    <option value="" disabled>{{ $t('choose_type') }}</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">{{ $t('current_image') }}</label>
                <div v-if="form.image" class="flex items-center gap-2">
                    <img :src="`/storage/${form.image}`" class="max-w-xs rounded shadow" />
                    <button type="button" @click="removeMainImage" class="text-red-600 hover:underline">{{ $t('delete') }}</button>
                </div>
            </div>

            <div>
                <label class="block mb-1 font-medium">{{ $t('upload_image') }}</label>
                <input type="file" @change="handleImageChange" accept="image/*" class="w-full border px-3 py-2 rounded">
                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" class="max-w-xs border rounded" />
                </div>
            </div>

            <div>
                <label class="block mb-1 font-medium">{{ $t('add_gallery') }}</label>
                <div class="flex gap-2 mb-2" v-if="form.images && form.images.length">
                    <div v-for="img in form.images" :key="img.id" class="relative">
                        <img :src="`/storage/${img.path}`" class="w-20 h-20 object-cover rounded" />
                        <button type="button" @click="removeGalleryImage(img.id)" class="absolute top-0 right-0 bg-white text-red-600 rounded-full p-1">&times;</button>
                    </div>
                </div>
                <input type="file" multiple @change="handleGalleryChange" accept="image/*" class="w-full border px-3 py-2 rounded">
                <div class="flex gap-2 mt-2">
                    <img v-for="(img, i) in galleryPreviews" :key="i" :src="img" class="w-20 h-20 object-cover rounded" />
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ $t('save_changes') }}
            </button>
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
const form = ref({})
const imageFile = ref(null)
const imagePreview = ref(null)
const categories = ref([])
const loaded = ref(false)
const galleryFiles = ref([])
const galleryPreviews = ref([])

const handleImageChange = (e) => {
    const file = e.target.files[0]
    if (!file) return

    const img = new Image()
    const objectURL = URL.createObjectURL(file)
    img.src = objectURL

    img.onload = () => {
        if (img.width > 600 || img.height > 600) {
            alert(t('image_size_error'))
            return
        }

        imageFile.value = file
        imagePreview.value = objectURL
    }
}
const handleGalleryChange = (e) => {
    const files = Array.from(e.target.files)
    galleryFiles.value = []
    galleryPreviews.value = []
    files.forEach(file => {
        galleryFiles.value.push(file)
        galleryPreviews.value.push(URL.createObjectURL(file))
    })
}

const removeMainImage = async () => {
    if (!confirm(t('delete_image_confirm'))) return
    await axios.delete(`/api/admin/products/${route.params.id}/image`)
    form.value.image = null
}

const removeGalleryImage = async (id) => {
    if (!confirm(t('delete_image_confirm'))) return
    await axios.delete(`/api/admin/product-images/${id}`)
    form.value.images = form.value.images.filter(img => img.id !== id)
}


const loadData = async () => {
    const productRes = await axios.get(`/api/admin/products/${route.params.id}`)
    form.value = productRes.data

    const catRes = await axios.get('/api/admin/categories')
    categories.value = catRes.data

    loaded.value = true
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
        }
        galleryFiles.value.forEach(file => {
            formData.append('images[]', file)
        })

        await axios.post(`/api/admin/products/${route.params.id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        alert(t('product_updated'))
        router.push({ name: 'products' })

    } catch (error) {
        console.error('Помилка при збереженні:', error)
        alert(t('product_update_error'))
    }
}

onMounted(loadData)
</script>
