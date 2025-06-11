<template>
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">{{ $t('banners') }}</h1>
            <router-link
                to="/admin/banners/create"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
                {{ $t('add_banner') }}
            </router-link>
        </div>

        <table class="min-w-full bg-white shadow rounded">
            <thead>
            <tr class="border-b bg-gray-100">
                <th class="text-left p-2">{{ $t('id') }}</th>
                <th class="text-left p-2">{{ $t('title') }}</th>
                <th class="text-left p-2">{{ $t('image') }}</th>
                <th class="text-left p-2">{{ $t('type') }}</th>
                <th class="text-left p-2">{{ $t('target') }}</th>
                <th class="text-left p-2">{{ $t('active') }}</th>
                <th class="text-left p-2">{{ $t('actions') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="banner in banners"
                :key="banner.id"
                class="border-b hover:bg-gray-50"
            >
                <td class="p-2">{{ banner.id }}</td>
                <td class="p-2">{{ banner.title }}</td>
                <td class="p-2">
                    <img
                        v-if="banner.image"
                        :src="`/storage/${banner.image}`"
                        class="h-12 rounded"
                    />
                </td>
                <td class="p-2">{{ banner.link_type }}</td>
                <td class="p-2">{{ banner.link_target }}</td>
                <td class="p-2">
                        <span
                            :class="{
                                'text-green-600': banner.is_active,
                                'text-red-600': !banner.is_active,
                            }"
                        >
                            {{ banner.is_active ? $t('yes') : $t('no') }}
                        </span>
                </td>
                <td class="p-2 space-x-2">
                    <router-link
                        :to="`/admin/banners/${banner.id}/edit`"
                        class="text-blue-600 hover:underline"
                    >
                        {{ $t('edit') }}
                    </router-link>
                    <button
                        @click="deleteBanner(banner.id)"
                        class="text-red-600 hover:underline"
                    >
                        {{ $t('delete') }}
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const banners = ref([])

const fetchBanners = async () => {
    const response = await axios.get('/api/admin/banners')
    banners.value = response.data
}

const deleteBanner = async (id) => {
    if (confirm(t('delete_banner_confirm'))) {
        await axios.delete(`/api/admin/banners/${id}`)
        await fetchBanners()
    }
}

onMounted(fetchBanners)
</script>
