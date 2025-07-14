<template>
    <div class="p-4 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ $t('add_product') }}</h1>

        <form @submit.prevent="handleSubmit" class="space-y-4">
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
                <label class="block mb-1 font-medium">{{ $t('upload_image') }}</label>
                <input type="file" @change="handleImageChange" accept="image/*" class="w-full border px-3 py-2 rounded">
                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" class="max-w-xs max-h-40 border rounded" />
                </div>
            </div>


            <div>
                <label class="block mb-1 font-medium">{{ $t('add_gallery') }}</label>
                <input type="file" multiple @change="handleGalleryChange" accept="image/*" class="w-full border px-3 py-2 rounded">
                <div class="flex gap-2 mt-2">
                    <img v-for="(img, i) in galleryPreviews" :key="i" :src="img" class="w-20 h-20 object-cover rounded" />
                </div>
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
                <label class="block mb-1 font-medium">{{ $t('brand') }}</label>
                <input v-model="form.brand" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('manufacturer') }}</label>
                <input v-model="form.manufacturer" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('country') }}</label>
                <input v-model="form.country" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('region') }}</label>
                <input v-model="form.region" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('classification') }}</label>
                <input v-model="form.classification" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('type') }}</label>
                <input v-model="form.type" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('package_type') }}</label>
                <input v-model="form.package_type" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('color') }}</label>
                <input v-model="form.color" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('sugar_content') }}</label>
                <input v-model="form.sugar_content" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('volume') }}</label>
                <input v-model="form.volume" type="number" step="0.01" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('sort') }}</label>
                <input v-model="form.sort" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('taste') }}</label>
                <input v-model="form.taste" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('aroma') }}</label>
                <input v-model="form.aroma" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('pairing') }}</label>
                <input v-model="form.pairing" type="text" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ $t('old_price') }}</label>
                <input v-model="form.old_price" type="number" step="0.01" class="w-full border px-3 py-2 rounded">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ $t('save') }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const router = useRouter()

const form = ref({
    name: '',
    price: '',
    description: '',
    category_id: '',
    brand: '',
    manufacturer: '',
    country: '',
    region: '',
    classification: '',
    type: '',
    package_type: '',
    color: '',
    sugar_content: '',
    volume: '',
    sort: '',
    taste: '',
    aroma: '',
    pairing: '',
    old_price: ''
})

const imageFile = ref(null)
const imagePreview = ref(null)
const galleryFiles = ref([])
const galleryPreviews = ref([])

const categories = ref([])

onMounted(async () => {
    const response = await axios.get('/api/admin/categories')
    categories.value = response.data
})

const handleImageChange = (e) => {
    const file = e.target.files[0]
    if (!file) return

    const img = new Image()
    const objectURL = URL.createObjectURL(file)
    img.src = objectURL

    img.onload = () => {
        if (img.width > 600 || img.height > 600) {
            alert(t('image_size_error'))
            imageFile.value = null
            imagePreview.value = null
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

const handleSubmit = async () => {
    try {
        const formData = new FormData()
        formData.append('name', form.value.name)
        formData.append('price', form.value.price)
        formData.append('description', form.value.description)
        formData.append('category_id', form.value.category_id)
        formData.append('brand', form.value.brand)
        formData.append('manufacturer', form.value.manufacturer)
        formData.append('country', form.value.country)
        formData.append('region', form.value.region)
        formData.append('classification', form.value.classification)
        formData.append('type', form.value.type)
        formData.append('package_type', form.value.package_type)
        formData.append('color', form.value.color)
        formData.append('sugar_content', form.value.sugar_content)
        formData.append('volume', form.value.volume)
        formData.append('sort', form.value.sort)
        formData.append('taste', form.value.taste)
        formData.append('aroma', form.value.aroma)
        formData.append('pairing', form.value.pairing)
        formData.append('old_price', form.value.old_price)
        if (imageFile.value) {
            formData.append('image', imageFile.value)
        }
        galleryFiles.value.forEach(file => {
            formData.append('images[]', file)
        })

        await axios.post('/api/admin/products', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })

        alert(t('product_created'))
        router.push({ name: 'products' })
    } catch (error) {
        if (error.response?.status === 422) {
            const errors = error.response.data.errors
            let msg = ''
            for (const key in errors) {
                msg += `${key}: ${errors[key].join(', ')}\n`
            }
            alert(t('validation_error') + '\n' + msg)
        } else {
            alert(t('product_save_error'))
        }
        console.error(error)
    }
}
</script>
