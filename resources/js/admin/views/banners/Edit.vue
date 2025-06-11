<template>
    <div class="p-4 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ $t('edit') }} {{ $t('banners') }}</h1>

        <form @submit.prevent="handleSubmit" class="space-y-4" v-if="loaded">
            <div>
                <label class="block font-medium mb-1">{{ $t('title') }}</label>
                <input v-model="form.title" type="text" class="w-full border px-3 py-2 rounded" required />
            </div>

            <div>
                <label class="block font-medium mb-1">{{ $t('subtitle') }}</label>
                <input v-model="form.subtitle" type="text" class="w-full border px-3 py-2 rounded" />
            </div>

            <div>
                <label class="block font-medium mb-1">{{ $t('link_type') }}</label>
                <select v-model="form.link_type" class="w-full border px-3 py-2 rounded" required>
                    <option value="">{{ $t('choose_type') }}</option>
                    <option value="product">{{ $t('product') }}</option>
                    <option value="category">{{ $t('category') }}</option>
                    <option value="custom">{{ $t('custom_url') }}</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">{{ $t('link_target') }}</label>
                <input v-model="form.link_target" type="text" class="w-full border px-3 py-2 rounded" required />
            </div>

            <div>
                <label class="block font-medium mb-1">{{ $t('button_text') }}</label>
                <input v-model="form.button_text" type="text" class="w-full border px-3 py-2 rounded" />
            </div>

            <div>
                <label class="block font-medium mb-1">{{ $t('is_active') }}</label>
                <input type="checkbox" v-model="form.is_active" />
            </div>

            <div>
                <label class="block font-medium mb-1">{{ $t('current_image') }}</label>
                <img :src="`/storage/${form.image}`" class="max-w-full border rounded shadow" v-if="form.image" />
            </div>

            <div>
                <label class="block font-medium mb-1">{{ $t('replace_image') }}</label>
                <input type="file" accept="image/*" @change="handleImageChange" class="w-full border px-3 py-2 rounded" />
                <p class="text-sm text-gray-500">{{ $t('max_dimensions') }}</p>

                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" class="max-w-full border rounded shadow" />
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
const loaded = ref(false)

const loadBanner = async () => {
    try {
        const response = await axios.get(`/api/admin/banners/${route.params.id}`)
        form.value = response.data
        loaded.value = true
    } catch (error) {
        alert(t('banner_load_error'))
        console.error(error)
    }
}

const handleImageChange = (e) => {
    const file = e.target.files[0]
    if (!file) return

    const img = new Image()
    const url = URL.createObjectURL(file)
    img.src = url

    img.onload = () => {
        if (img.width > 1100 || img.height > 300) {
            alert(t('image_size_error'))
            return
        }

        imageFile.value = file
        imagePreview.value = url
    }
}

const handleSubmit = async () => {
    const formData = new FormData()
    formData.append('_method', 'PUT')

    for (const key in form.value) {
        formData.append(key, form.value[key])
    }

    if (imageFile.value) {
        formData.append('image', imageFile.value)
    }

    try {
        await axios.post(`/api/admin/banners/${route.params.id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
        alert(t('banner_updated'))
        router.push({ name: 'banners' })
    } catch (e) {
        alert(t('banner_update_error'))
        console.error(e)
    }
}

onMounted(loadBanner)
</script>
