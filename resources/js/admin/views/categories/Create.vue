<template>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Заголовок з кнопкою назад -->
        <div class="flex items-center mb-6">
            <router-link
                to="/admin/categories"
                class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                {{ $t('back_to_categories') }}
            </router-link>
            <h1 class="text-2xl font-bold text-gray-800 ml-4">{{ $t('add_category') }}</h1>
        </div>

        <!-- Форма створення -->
        <div class="bg-white rounded-xl shadow-md p-6 sm:p-8">
            <form @submit.prevent="submit">
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $t('name') }} <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="form.name"
                        id="name"
                        type="text"
                        :placeholder="$t('category_name_placeholder')"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        :class="{ 'border-red-500': errors.name }"
                        required
                        @input="generateSlug"
                    >
                    <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                </div>

                <div class="mb-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $t('slug') }}
                        <span class="text-xs text-gray-500 ml-1">({{ $t('auto_generated') }})</span>
                    </label>
                    <div class="relative">
                        <input
                            v-model="form.slug"
                            id="slug"
                            type="text"
                            :placeholder="$t('category_slug_placeholder')"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition pr-10"
                            :class="{ 'border-red-500': errors.slug }"
                            @input="slugManuallyEdited = true"
                        >
                        <button
                            type="button"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors"
                            @click="resetAutoSlug"
                            :title="$t('regenerate_slug')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <p v-if="errors.slug" class="mt-2 text-sm text-red-600">{{ errors.slug }}</p>
                    <p class="mt-2 text-sm text-gray-500">{{ $t('slug_description') }}</p>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ $t('slug_hint') }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <button
                        type="submit"
                        class="btn-primary flex items-center justify-center"
                        :disabled="loading"
                    >
                        <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span v-if="!loading">{{ $t('save') }}</span>
                        <span v-else>{{ $t('saving') }}</span>
                    </button>

                    <router-link
                        to="/admin/categories"
                        class="btn-secondary"
                    >
                        {{ $t('cancel') }}
                    </router-link>
                </div>
            </form>
        </div>

        <!-- Тостові сповіщення -->
        <ToastNotification
            v-if="showToast"
            :type="toastType"
            :message="toastMessage"
            @close="showToast = false"
        />
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const router = useRouter()
const loading = ref(false)
const showToast = ref(false)
const toastMessage = ref('')
const toastType = ref('success')
const slugManuallyEdited = ref(false)

const form = reactive({
    name: '',
    slug: ''
})

const errors = reactive({
    name: '',
    slug: ''
})

// Функція транслітерації кирилиці
const transliterate = (text) => {
    const cyrillicMap = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'є': 'ye', 'ж': 'zh', 'з': 'z', 'и': 'i',
        'і': 'i', 'ї': 'yi', 'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o', 'п': 'p', 'р': 'r',
        'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'kh', 'ц': 'ts', 'ч': 'ch', 'ш': 'sh', 'щ': 'shch',
        'ь': '', 'ю': 'yu', 'я': 'ya',
        'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Є': 'Ye', 'Ж': 'Zh', 'З': 'Z', 'И': 'I',
        'І': 'I', 'Ї': 'Yi', 'Й': 'Y', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N', 'О': 'O', 'П': 'P', 'Р': 'R',
        'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Х': 'Kh', 'Ц': 'Ts', 'Ч': 'Ch', 'Ш': 'Sh', 'Щ': 'Shch',
        'Ь': '', 'Ю': 'Yu', 'Я': 'Ya',
        ' ': '-', '_': '-', ',': '-', ';': '-', '.': '-', '!': '-', '?': '-',
        '(': '-', ')': '-', '[': '-', ']': '-', '{': '-', '}': '-', '/': '-', '\\': '-', '@': '-', '#': '-',
        '$': '-', '%': '-', '^': '-', '&': '-', '*': '-', '+': '-', '=': '-', '|': '-', '~': '-', '`': '-',
        "'": '-', '"': '-', '<': '-', '>': '-', '№': '-'
    };

    return text.split('').map(char => {
        return cyrillicMap[char] || char;
    }).join('');
};

// Покращена функція slugify з підтримкою кирилиці
const slugify = (text) => {
    if (!text) return '';

    // Спочатку транслітеруємо кирилицю
    const transliterated = transliterate(text);

    // Потім застосовуємо звичайну обробку
    return transliterated
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
};

// Автоматичне генерування slug з назви
const generateSlug = () => {
    if (!slugManuallyEdited.value) {
        form.slug = slugify(form.name)
    }
}

// Дозволити користувачу скинути до автоматичного slug
const resetAutoSlug = () => {
    form.slug = slugify(form.name)
    slugManuallyEdited.value = false
}

const showNotification = (message, type) => {
    toastMessage.value = message
    toastType.value = type
    showToast.value = true
    setTimeout(() => showToast.value = false, 3000)
}

const validateForm = () => {
    let valid = true
    errors.name = ''
    errors.slug = ''

    if (!form.name.trim()) {
        errors.name = t('name_required')
        valid = false
    } else if (form.name.length > 50) {
        errors.name = t('name_too_long')
        valid = false
    }

    // Генерувати slug автоматично, якщо поле порожнє
    if (!form.slug) {
        form.slug = slugify(form.name)
    }

    if (form.slug) {
        if (!/^[a-z0-9\-]+$/.test(form.slug)) {
            errors.slug = t('invalid_slug_format')
            valid = false
        } else if (form.slug.length > 60) {
            errors.slug = t('slug_too_long')
            valid = false
        }
    }

    return valid
}

const submit = async () => {
    if (!validateForm()) return

    try {
        loading.value = true

        // Гарантуємо, що slug буде створено, навіть якщо користувач його не вводив
        const slugToSend = form.slug || slugify(form.name)

        const response = await axios.post('/api/admin/categories', {
            name: form.name,
            slug: slugToSend
        })

        if (response.status === 201) {
            showNotification(t('category_created'), 'success')
            setTimeout(() => router.push('/admin/categories'), 1500)
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            // Обробка помилок валідації з сервера
            const serverErrors = error.response.data.errors
            if (serverErrors.name) {
                errors.name = serverErrors.name[0]
            }
            if (serverErrors.slug) {
                // Спеціальна обробка помилки унікальності
                if (serverErrors.slug[0].includes('unique')) {
                    errors.slug = t('slug_must_be_unique')
                } else {
                    errors.slug = serverErrors.slug[0]
                }
            }
        } else {
            showNotification(t('creation_error'), 'error')
        }
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.btn-primary {
    @apply flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg
    hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2
    focus:ring-blue-500 transition-colors shadow-sm;
}

.btn-primary:disabled {
    @apply bg-blue-400 cursor-not-allowed;
}

.btn-secondary {
    @apply flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-medium rounded-lg
    border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2
    focus:ring-gray-500 transition-colors shadow-sm;
}
</style>
