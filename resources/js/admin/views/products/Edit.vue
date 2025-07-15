<template>
    <div class="p-6 max-w-5xl mx-auto bg-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">{{ $t('edit') }} {{ $t('products') }}</h1>

        <form @submit.prevent="handleSubmit" v-if="loaded">
            <Tabs>
                <template #main>
                    <MainTab :form="form" :categoryOptions="categoryOptions" />
                </template>

                <template #attributes>
                    <AttributesTab :form="form" />
                </template>

                <template #images>
                    <ImagesTab
                        :form="form"
                        :imageFile="imageFile"
                        :imagePreview="imagePreview"
                        :galleryPreviews="galleryPreviews"
                        @image-change="handleImageChange"
                        @gallery-change="handleGalleryChange"
                        @remove-main="removeMainImage"
                        @remove-gallery="removeGalleryImage"
                    />
                </template>
            </Tabs>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 mt-8">
                <button type="button" @click="router.back()" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    {{ $t('cancel') }}
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ $t('save_changes') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useI18n } from 'vue-i18n'
import Tabs from '@/admin/components/ui/Tabs.vue'
import MainTab from './Tabs/MainTab.vue'
import AttributesTab from './Tabs/AttributesTab.vue'
import ImagesTab from './Tabs/ImagesTab.vue'

const { t } = useI18n()
const route = useRoute()
const router = useRouter()

const form = ref({
    name: '', price: 0, description: '', category_id: '', image: null, images: [], brand: '', manufacturer: '', country: '', region: '', classification: '', type: '', package_type: '', color: '', sugar_content: '', volume: '', sort: '', taste: '', aroma: '', pairing: '', old_price: ''
})

const imageFile = ref(null)
const imagePreview = ref(null)
const categories = ref([])
const loaded = ref(false)
const galleryFiles = ref([])
const galleryPreviews = ref([])

const attributeFields = [
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

const appendNullableNumber = (fd, key, value) => {
    if (value !== '' && value !== null && value !== undefined && !isNaN(value)) {
        fd.append(key, value)
    }
}

const fetchCategories = async () => {
    const response = await axios.get('/api/admin/categories/all-flat')
    categories.value = response.data
}

function buildOptions(list, parentId = null, level = 0) {
    let options = []
    list.filter(c => c.parent_id === parentId).forEach(cat => {
        options.push({ id: cat.id, name: `${'— '.repeat(level)}${cat.name}` })
        options = options.concat(buildOptions(list, cat.id, level + 1))
    })
    return options
}
const categoryOptions = computed(() => buildOptions(categories.value))

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
        const [productRes] = await Promise.all([
            axios.get(`/api/admin/products/${route.params.id}`),
            fetchCategories()
        ])
        form.value = productRes.data
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
        Object.keys(form.value).forEach(key => {
            if (['old_price', 'volume'].includes(key)) {
                appendNullableNumber(formData, key, form.value[key])
            } else if (form.value[key] !== undefined && form.value[key] !== null) {
                formData.append(key, form.value[key])
            }
        })

        if (imageFile.value) {
            formData.append('image', imageFile.value)
        } else if (form.value.image === null) {
            formData.append('image_deleted', 'true')
        } else {
            formData.append('image', '') // <- додано щоб Laravel не впав
        }

        galleryFiles.value.forEach(file => {
            formData.append('images[]', file)
        })

        await axios.post(`/api/admin/products/${route.params.id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })

        alert(t('product_updated'))
        router.push({ name: 'products' })
    } catch (error) {
        if (error.response?.status === 422) {
            console.error('Validation Errors:', error.response.data.errors)
        } else {
            console.error('Помилка при збереженні:', error)
        }
        alert(t('product_update_error'))
    }
}

onMounted(loadData)
</script>
