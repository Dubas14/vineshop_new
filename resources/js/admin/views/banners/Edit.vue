<template>
    <div class="p-4 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Редагувати банер</h1>

        <form @submit.prevent="handleSubmit" class="space-y-4" v-if="loaded">
            <div>
                <label class="block font-medium mb-1">Заголовок</label>
                <input v-model="form.title" type="text" class="w-full border px-3 py-2 rounded" required />
            </div>

            <div>
                <label class="block font-medium mb-1">Підзаголовок</label>
                <input v-model="form.subtitle" type="text" class="w-full border px-3 py-2 rounded" />
            </div>

            <div>
                <label class="block font-medium mb-1">Тип посилання</label>
                <select v-model="form.link_type" class="w-full border px-3 py-2 rounded" required>
                    <option value="">Оберіть тип</option>
                    <option value="product">Товар</option>
                    <option value="category">Категорія</option>
                    <option value="custom">Свій URL</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Ціль посилання</label>
                <input v-model="form.link_target" type="text" class="w-full border px-3 py-2 rounded" required />
            </div>

            <div>
                <label class="block font-medium mb-1">Текст кнопки</label>
                <input v-model="form.button_text" type="text" class="w-full border px-3 py-2 rounded" />
            </div>

            <div>
                <label class="block font-medium mb-1">Активний</label>
                <input type="checkbox" v-model="form.is_active" />
            </div>

            <div>
                <label class="block font-medium mb-1">Поточне зображення</label>
                <img :src="`/storage/${form.image}`" class="max-w-full border rounded shadow" v-if="form.image" />
            </div>

            <div>
                <label class="block font-medium mb-1">Замінити зображення</label>
                <input type="file" accept="image/*" @change="handleImageChange" class="w-full border px-3 py-2 rounded" />
                <p class="text-sm text-gray-500">Макс: 1100px по ширині, 300px по висоті</p>

                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" class="max-w-full border rounded shadow" />
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Зберегти зміни
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

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
        alert('Не вдалося завантажити банер')
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
            alert('Зображення має бути не більше 1100px в ширину та 300px у висоту')
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
        alert('Банер оновлено')
        router.push({ name: 'banners' })
    } catch (e) {
        alert('Помилка при оновленні банера')
        console.error(e)
    }
}

onMounted(loadBanner)
</script>
