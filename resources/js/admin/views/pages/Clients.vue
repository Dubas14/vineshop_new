<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $t('clients.title') }}</h1>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
            <tr>
                <th class="px-4 py-2">{{ $t('clients.name') }}</th>
                <th class="px-4 py-2">{{ $t('clients.phone') }}</th>
                <th class="px-4 py-2">E-mail</th>
                <th class="px-4 py-2">{{ $t('clients.registration_date') }}</th>
                <th class="px-4 py-2">{{ $t('clients.orders_count') }}</th>
                <th class="px-4 py-2">{{ $t('clients.orders_sum') }}</th>
                <th class="px-4 py-2">{{ $t('clients.action') }}</th>
                <th class="px-4 py-2">{{ $t('clients.discount') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="client in clients" :key="client.id" class="border-b">
                <td class="px-4 py-2">{{ client.name }}</td>
                <td class="px-4 py-2">{{ client.phone }}</td>
                <td class="px-4 py-2">{{ client.email }}</td>
                <td class="px-4 py-2">{{ client.created_at }}</td>
                <td class="px-4 py-2">{{ client.orders_count }}</td>
                <td class="px-4 py-2">{{ client.orders_sum }} грн.</td>
                <td class="px-4 py-2">
                    <button
                        class="text-red-600 hover:underline"
                        @click="deleteClient(client.id)"
                    >
                        {{ $t('clients.delete') }}
                    </button>
                </td>
                <td class="px-4 py-2 flex items-center gap-2">
                    <input
                        type="number"
                        min="0"
                        max="99.99"
                        step="0.01"
                        v-model="client.discount"
                        class="w-16 border px-2 py-1 rounded"
                        :placeholder="$t('clients.discount')"
                    /> %
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs"
                        @click="saveDiscount(client)"
                    >
                        {{ $t('clients.save') }}
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from 'axios'

const { t: $t } = useI18n()

const saveDiscount = async (client) => {
    try {
        await axios.put(`/api/admin/clients/${client.id}`, {
            discount: client.discount,
        });
        alert($t('clients.save_success'));
    } catch (e) {
        alert($t('clients.save_error'));
    }
};

const clients = ref([])

onMounted(async () => {
    const res = await axios.get('/api/admin/clients')
    clients.value = res.data
})

const deleteClient = async (id) => {
    if (!confirm($t('clients.delete_confirm'))) return;
    try {
        await axios.delete(`/api/admin/clients/${id}`);
        clients.value = clients.value.filter(c => c.id !== id);
        alert($t('clients.deleted'));
    } catch (e) {
        alert($t('clients.delete_error'));
    }
};
</script>
