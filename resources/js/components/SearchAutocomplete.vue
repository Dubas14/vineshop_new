<template>
    <div class="relative w-full max-w-lg" @click.outside="show = false">
        <input
            v-model="search"
            @input="onInput"
            @focus="show = true"
            type="text"
            class="border rounded px-4 py-2 w-full"
            placeholder="Я шукаю..."
        />

        <div v-if="show && (products.length || categories.length || noResults)"
             class="absolute left-0 right-0 bg-white border z-50 shadow max-h-96 overflow-y-auto mt-1 rounded">
            <template v-if="products.length">
                <div class="px-4 py-2 font-bold text-gray-600 bg-gray-100">Товари</div>
                <a v-for="p in products" :key="p.id" :href="`/product/${p.slug}`"
                   class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 border-b">
                    <img :src="getImage(p.image)" class="w-12 h-12 object-contain rounded" />
                    <div>
                        <div class="font-medium">{{ p.name }}</div>
                        <div class="text-xs text-gray-500">Код: {{ p.sku }}</div>
                        <div class="text-red-600 font-bold">{{ p.price }} грн</div>
                    </div>
                </a>
            </template>

            <template v-if="categories.length">
                <div class="px-4 py-2 font-bold text-gray-600 bg-gray-100">Категорії</div>
                <a v-for="c in categories" :key="c.id" :href="`/catalog/category/${c.slug}`"
                   class="block px-4 py-2 hover:bg-gray-50 border-b">{{ c.name }}</a>
            </template>

            <div v-if="noResults" class="px-4 py-3 text-gray-400">Нічого не знайдено</div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { debounce } from 'lodash-es'

const search = ref('')
const products = ref([])
const categories = ref([])
const show = ref(false)
const noResults = ref(false)

const fetchResults = debounce(async () => {
    if (search.value.length < 2) {
        products.value = []
        categories.value = []
        noResults.value = false
        return
    }
    const { data } = await axios.get('/search/autocomplete', {
        params: { query: search.value }
    })

    products.value = data.products
    categories.value = data.categories
    noResults.value = !(data.products.length || data.categories.length)
}, 300)

function onInput() {
    show.value = true
    fetchResults()
}

function getImage(path) {
    return path ? `/storage/${path}` : '/images/no-image.png'
}
</script>
