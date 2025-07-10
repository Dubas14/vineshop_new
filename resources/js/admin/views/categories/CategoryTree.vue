<template>
    <div>
        <div class="flex items-center border-b py-2 pl-2" :style="{paddingLeft: `${depth * 32}px`}">
            <div class="flex items-center gap-3 w-1/2">
                <button
                    v-if="hasChildren"
                    @click="isExpanded = !isExpanded"
                    class="flex-shrink-0 h-5 w-5 text-gray-500 hover:text-gray-700 focus:outline-none"
                >
                    <ChevronRightIcon
                        class="h-5 w-5 transition-transform duration-200"
                        :class="{ 'transform rotate-90': isExpanded }"
                    />
                </button>
                <div v-else class="flex-shrink-0 h-5 w-5"></div>

                <div class="flex-shrink-0 h-9 w-9 rounded-md bg-blue-100 flex items-center justify-center">
                    <FolderIcon class="h-5 w-5 text-blue-600" />
                </div>
                <div>
                    <div class="font-medium text-gray-900">{{ category.name }}</div>
                    <div class="text-xs text-gray-500">ID: {{ category.id }}</div>
                </div>
            </div>
            <div class="flex gap-1 ml-auto">
                <router-link
                    :to="`/admin/categories/${category.id}/edit`"
                    class="btn-icon text-blue-600 hover:bg-blue-100"
                    :title="$t('edit')"
                >
                    <PencilIcon class="h-4 w-4" />
                </router-link>
                <button
                    @click="deleteCategory(category.id)"
                    class="btn-icon text-red-600 hover:bg-red-100"
                    :title="$t('delete')"
                >
                    <TrashIcon class="h-4 w-4" />
                </button>
                <button @click="showInput = !showInput" class="btn-icon text-blue-600">+</button>
            </div>
        </div>
        <div v-if="showInput" class="flex items-center gap-2 ml-12 my-2">
            <input v-model="newName" class="border px-2 py-1 rounded" placeholder="Назва підкатегорії" />
            <button @click="addSubcategory" class="btn-primary px-3 py-1 text-sm">Додати</button>
            <button @click="showInput = false" class="btn-secondary px-2 py-1 text-sm">Скасувати</button>
        </div>
        <div v-if="isExpanded">
            <CategoryTree
                v-for="child in category.children"
                :key="child.id"
                :category="child"
                :depth="depth + 1"
                @refresh="$emit('refresh')"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { PencilIcon, TrashIcon, FolderIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'
import axios from 'axios'

const props = defineProps({
    category: Object,
    depth: { type: Number, default: 0 }
})
const emit = defineEmits(['refresh'])
const { t } = useI18n()

const showInput = ref(false)
const newName = ref('')
const isExpanded = ref(true) // Initially expanded

const hasChildren = computed(() => {
    return props.category.children && props.category.children.length > 0
})

const addSubcategory = async () => {
    if (!newName.value.trim()) return
    const slug = newName.value.trim()
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^a-z0-9\-а-яіїєґ]/gi, '')
    await axios.post('/api/admin/categories', {
        name: newName.value,
        slug: slug,
        parent_id: props.category.id
    })
    newName.value = ''
    showInput.value = false
    isExpanded.value = true // Expand parent when adding new subcategory
    emit('refresh')
}

const deleteCategory = async (id) => {
    if (!confirm(t('delete_category_confirm'))) return
    await axios.delete(`/api/admin/categories/${id}`)
    emit('refresh')
}
</script>
