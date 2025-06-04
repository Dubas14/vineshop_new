<template>
    <div class="p-4 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Додати товар</h1>

        <form @submit.prevent="handleSubmit" class="space-y-4">
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
                <label class="block mb-1 font-medium">Завантажити зображення</label>
                <input type="file" @change="handleImageChange" accept="image/*" class="w-full border px-3 py-2 rounded">
                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" class="max-w-xs max-h-40 border rounded" />
                </div>
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

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Зберегти
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
    name: '',
    price: '',
    description: '',
    category_id: ''
})

const imageFile = ref(null)
const imagePreview = ref(null)

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
            alert('Зображення повинно бути не більше 600 пікселів по ширині або висоті.')
            imageFile.value = null
            imagePreview.value = null
            return
        }

        imageFile.value = file
        imagePreview.value = objectURL
    }
}

const handleSubmit = async () => {
    try {
        const formData = new FormData()
        formData.append('name', form.value.name)
        formData.append('price', form.value.price)
        formData.append('description', form.value.description)
        formData.append('category_id', form.value.category_id)
        if (imageFile.value) {
            formData.append('image', imageFile.value)
        }

        await axios.post('/api/admin/products', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })

        alert('Товар створено!')
        router.push({ name: 'products' })
    } catch (error) {
        if (error.response?.status === 422) {
            const errors = error.response.data.errors
            let msg = ''
            for (const key in errors) {
                msg += `${key}: ${errors[key].join(', ')}\n`
            }
            alert('Помилка валідації:\n' + msg)
        } else {
            alert('Помилка при збереженні товару')
        }
        console.error(error)
    }
}
</script>
