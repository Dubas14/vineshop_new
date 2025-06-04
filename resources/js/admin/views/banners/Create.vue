<template>
    <div class="p-4 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Додати банер</h1>

        <form @submit.prevent="handleSubmit" class="space-y-4">
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
                <p class="text-sm text-gray-500">
                    ID товару / ID категорії / повний URL (залежно від типу)
                </p>
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
                <label class="block font-medium mb-1">Завантажити зображення</label>
                <input type="file" accept="image/*" @change="handleImageChange" class="w-full border px-3 py-2 rounded" />
                <p class="text-sm text-gray-500">Максимум: 1100px по ширині та 300px по висоті</p>

                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" class="max-w-full border rounded shadow" />
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Зберегти
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
    title: '',
    subtitle: '',
    link_type: '',
    link_target: '',
    button_text: '',
    is_active: true
})

const imageFile = ref(null)
const imagePreview = ref(null)

const handleImageChange = (e) => {
    const file = e.target.files[0]
    if (!file) return

    const img = new Image()
    const url = URL.createObjectURL(file)
    img.src = url

    img.onload = () => {
        if (img.width > 1100 || img.height > 300) {
            alert('Зображення має бути не більше 1100px в ширину та 300px у висоту')
            imageFile.value = null
            imagePreview.value = null
            return
        }

        imageFile.value = file
        imagePreview.value = url
    }
}

const handleSubmit = async () => {
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('subtitle', form.value.subtitle || '')
    formData.append('link_type', form.value.link_type)
    formData.append('link_target', form.value.link_target)
    formData.append('button_text', form.value.button_text || '')
    formData.append('is_active', form.value.is_active ? 1 : 0)

    if (imageFile.value) {
        formData.append('image', imageFile.value)
    }

    try {
        await axios.post('/api/admin/banners', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
        alert('Банер додано!')
        router.push({ name: 'banners' })
    } catch (e) {
        alert('Помилка при збереженні банера')
        console.error(e)
    }
}
</script>
