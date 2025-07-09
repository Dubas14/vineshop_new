<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Клієнти</h1>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
            <tr>
                <th class="px-4 py-2">Ім'я прізвище</th>
                <th class="px-4 py-2">Телефон</th>
                <th class="px-4 py-2">E-mail</th>
                <th class="px-4 py-2">Дата реєстрації</th>
                <th class="px-4 py-2">К-сть замовлень</th>
                <th class="px-4 py-2">Сума замовлень</th>
                <th class="px-4 py-2">Дія</th>
                <th class="px-4 py-2">Знижка</th>
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
                        Видалити
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
                    /> %
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs"
                        @click="saveDiscount(client)"
                    >
                        Зберегти
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

const saveDiscount = async (client) => {
    try {
        await axios.put(`/api/admin/clients/${client.id}`, {
            discount: client.discount,
        });
        alert('Знижку оновлено!');
    } catch (e) {
        alert('Помилка при збереженні!');
    }
};

const clients = ref([])

onMounted(async () => {
    const res = await axios.get('/api/admin/clients')
    clients.value = res.data
})

const deleteClient = async (id) => {
    if (!confirm("Ви точно хочете видалити цього клієнта? Цю дію неможливо відмінити!")) return;
    try {
        await axios.delete(`/api/admin/clients/${id}`);
        clients.value = clients.value.filter(c => c.id !== id);
        alert('Клієнта видалено!');
    } catch (e) {
        alert('Помилка при видаленні!');
    }
};
</script>
