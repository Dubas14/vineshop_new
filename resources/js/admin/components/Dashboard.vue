<template>
    <div class="p-4 space-y-6">
        <h1 class="text-2xl font-bold mb-4">{{ $t('admin_dashboard') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded shadow">
                <p class="text-gray-500 text-sm">{{ $t('orders_last_week') }}</p>
                <p class="text-lg font-semibold text-red-600">0 грн</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-gray-500 text-sm">{{ $t('orders_this_week') }}</p>
                <p class="text-lg font-semibold">0</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-gray-500 text-sm">{{ $t('new_customers') }}</p>
                <p class="text-lg font-semibold">0</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-gray-500 text-sm">{{ $t('visitors') }}</p>
                <p class="text-lg font-semibold text-blue-600">0</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-700 font-semibold mb-4">{{ $t('orders_by_category') }}</p>
            <div class="max-w-[300px] mx-auto">
                <DonutChart />
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-gray-700 font-semibold mb-4">{{ $t('sales_dynamics_week') }}</p>
                <div class="h-[250px]">
                    <LineChart />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import DonutChart from './DonutChart.vue'
import LineChart from './LineChart.vue'
</script>
