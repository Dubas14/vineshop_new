<template>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">{{ $t('edit_category') }}</h1>

            <div v-if="loading" class="text-center py-8">
                <div class="loader mx-auto"></div>
                <p class="mt-3 text-gray-600">{{ $t('loading') }}...</p>
            </div>

            <div v-else>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('name') }}</label>
                        <input
                            v-model="form.name"
                            @input="generateSlug"
                            type="text"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            :class="{ 'border-red-500': errors.name, 'border-gray-300': !errors.name }"
                            required
                            placeholder="Назва категорії"
                        />
                        <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $t('slug') }}
                            <span class="text-xs text-gray-500 ml-1">({{ $t('auto_generated') }})</span>
                        </label>
                        <input
                            v-model="form.slug"
                            @input="slugManuallyEdited = true"
                            type="text"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            :class="{ 'border-red-500': errors.slug, 'border-gray-300': !errors.slug }"
                            placeholder="slug-категорії"
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $t('slug_hint') }}
                        </p>
                        <p v-if="errors.slug" class="mt-2 text-sm text-red-600">{{ errors.slug }}</p>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button
                            type="submit"
                            class="btn-primary flex items-center justify-center"
                            :disabled="loading"
                        >
                            <span v-if="!loading">{{ $t('save') }}</span>
                            <span v-else class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ $t('saving') }}...
                            </span>
                        </button>
                        <router-link
                            to="/admin/categories"
                            class="btn-secondary flex items-center justify-center"
                        >
                            {{ $t('cancel') }}
                        </router-link>
                    </div>
                </form>
            </div>
        </div>

        <ToastNotification
            v-if="showToast"
            :type="toastType"
            :message="toastMessage"
            @close="showToast = false"
        />
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const router = useRouter()
const route = useRoute()

const slugManuallyEdited = ref(false)
const loading = ref(false)
const showToast = ref(false)
const toastMessage = ref('')
const toastType = ref('success')
const categoryFound = ref(true)

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

const generateSlug = () => {
    if (!slugManuallyEdited.value) {
        form.slug = slugify(form.name)
    }
};

const showNotification = (message, type = 'success') => {
    toastMessage.value = message;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => showToast.value = false, 3000);
};

const fetchCategory = async () => {
    loading.value = true;
    try {
        const { data } = await axios.get(`/api/admin/categories/${route.params.id}`);
        form.name = data.name;
        form.slug = data.slug || slugify(data.name);
        categoryFound.value = true;
    } catch (error) {
        if (error.response?.status === 404) {
            showNotification(t('category_not_found'), 'error');
            categoryFound.value = false;
            setTimeout(() => router.push('/admin/categories'), 1500);
        } else {
            showNotification(t('fetch_error'), 'error');
        }
    } finally {
        loading.value = false;
    }
};

const validateForm = () => {
    let valid = true;
    errors.name = '';
    errors.slug = '';

    if (!form.name.trim()) {
        errors.name = t('name_required');
        valid = false;
    }

    if (!form.slug) {
        form.slug = slugify(form.name);
    }

    if (!/^[a-z0-9\-]+$/.test(form.slug)) {
        errors.slug = t('invalid_slug_format');
        valid = false;
    }

    return valid;
};

const submit = async () => {
    if (!validateForm()) return;

    try {
        loading.value = true;
        await axios.put(`/api/admin/categories/${route.params.id}`, {
            name: form.name,
            slug: form.slug
        });
        showNotification(t('category_updated'));
        setTimeout(() => {
            router.push('/admin/categories');
        }, 1500);
    } catch (error) {
        if (error.response?.status === 422) {
            const serverErrors = error.response.data.errors;
            if (serverErrors.name) errors.name = serverErrors.name[0];
            if (serverErrors.slug) {
                // Спеціальна обробка помилки унікальності
                if (serverErrors.slug[0].includes('unique')) {
                    errors.slug = t('slug_must_be_unique');
                } else {
                    errors.slug = serverErrors.slug[0];
                }
            }
        } else if (error.response?.status === 404) {
            showNotification(t('category_not_found'), 'error');
            setTimeout(() => router.push('/admin/categories'), 1500);
        } else {
            showNotification(t('update_error'), 'error');
        }
    } finally {
        loading.value = false;
    }
};

onMounted(fetchCategory);
</script>

<style scoped>
.btn-primary {
    background-color: #3b82f6;
    color: white;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-primary:hover {
    background-color: #2563eb;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.btn-primary:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
}

.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-secondary {
    background-color: #e5e7eb;
    color: #374151;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-secondary:hover {
    background-color: #d1d5db;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.btn-secondary:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(209, 213, 219, 0.5);
}

.input {
    padding: 0.75rem 1rem;
    border-width: 1px;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    border-color: #3b82f6;
}

.loader {
    display: inline-block;
    width: 3rem;
    height: 3rem;
    border-width: 4px;
    border-color: #3b82f6;
    border-top-color: transparent;
    border-radius: 9999px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.border-red-500 {
    border-color: #ef4444;
}
</style>
