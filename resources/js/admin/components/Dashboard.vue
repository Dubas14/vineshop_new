git <template>
    <div class="p-4 space-y-6">
        <h1 class="text-2xl font-bold mb-4">{{ $t('admin_dashboard') }}</h1>

        <!-- Картки статистики -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded shadow">
                <p class="text-gray-500 text-sm">{{ $t('orders_last_week') }}</p>
                <p class="text-lg font-semibold text-red-600">{{ stats.orders_last_week_sum ?? 0 }} грн</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-gray-500 text-sm">{{ $t('orders_this_week') }}</p>
                <p class="text-lg font-semibold">{{ stats.orders_this_week_count ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-gray-500 text-sm">{{ $t('new_customers') }}</p>
                <p class="text-lg font-semibold">{{ stats.new_customers ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-gray-500 text-sm">{{ $t('visitors') }}</p>
                <p class="text-lg font-semibold text-blue-600">{{ stats.visitors ?? 0 }}</p>
            </div>
        </div>

        <!-- Графіки -->
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-700 font-semibold mb-4">{{ $t('orders_by_category') }}</p>
            <div class="max-w-[300px] mx-auto">
                <DonutChart :labels="donutLabels" :data="donutData" />
            </div>
            <div class="bg-white p-4 rounded shadow mt-4">
                <p class="text-gray-700 font-semibold mb-4">{{ $t('sales_dynamics_week') }}</p>
                <div class="h-[250px]">
                    <LineChart :labels="lineLabels" :data="lineData" dataset-label="Продажі" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DonutChart from './DonutChart.vue'
import LineChart from './LineChart.vue'

const stats = ref({})
const donutLabels = ref([])
const donutData = ref([])
const lineLabels = ref([])
const lineData = ref([])

onMounted(async () => {
    // Основна статистика
    const statsRes = await axios.get('/api/admin/dashboard/stats')
    stats.value = statsRes.data

    // Графік по категоріям
    const donutRes = await axios.get('/api/admin/stat/categories')
    donutLabels.value = donutRes.data.labels
    donutData.value = donutRes.data.data

    // Графік продажів по дням
    const lineRes = await axios.get('/api/admin/stat/orders-per-day')
    lineLabels.value = lineRes.data.labels
    lineData.value = lineRes.data.data
})
</script>
