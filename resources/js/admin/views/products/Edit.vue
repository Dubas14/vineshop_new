<template>
    <div class="p-4 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Редагування товару</h1>

        <form @submit.prevent="handleSubmit" class="space-y-4" v-if="loaded">
            <div>
                <label class="block mb-1 font-medium">Назва</label>
                <input v-model="form.name" type="text" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">Ціна</label>
                <input v-model="form.price" type="number" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">Опис</label>
                <textarea v-model="form.description" class="w-full border px-3 py-2 rounded"></textarea>
            </div>

            <div>
                <label class="block mb-1 font-medium">Категорія</label>
                <select v-model="form.category_id" class="w-full border px-3 py-2 rounded" required>
                    <option value="" disabled>Оберіть категорію</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">Поточне зображення</label>
                <img v-if="form.image" :src="`/storage/${form.image}`" class="max-w-xs rounded shadow" />
            </div>

            <div>
                <label class="block mb-1 font-medium">Завантажити нове зображення</label>
                <input type="file" @change="handleImageChange" accept="image/*" class="w-full border px-3 py-2 rounded">
                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" class="max-w-xs border rounded" />
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
const categories = ref([])
const loaded = ref(false)

const handleImageChange = (e) => {
    const file = e.target.files[0]
    if (!file) return

    const img = new Image()
    const objectURL = URL.createObjectURL(file)
    img.src = objectURL

    img.onload = () => {
        if (img.width > 600 || img.height > 600) {
            alert('Зображення має бути не більше 600 пікселів по ширині або висоті.')
            return
        }

        imageFile.value = file
        imagePreview.value = objectURL
    }
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

        await axios.post(`/api/admin/products/${route.params.id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        alert('Зміни збережено')
        router.push({ name: 'products' })

    } catch (error) {
        console.error('Помилка при збереженні:', error)
        alert('Помилка при збереженні. Перевірте поля та спробуйте ще раз.')
    }
}

onMounted(loadData)
</script>
