<template>
    <div>
        <Doughnut :data="chartData" :options="chartOptions" />
    </div>
</template>

<script setup>
import { Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js'
import { computed } from 'vue'

ChartJS.register(Title, Tooltip, Legend, ArcElement)

// Приймаємо через props з Dashboard.vue
const props = defineProps({
    labels: { type: Array, required: true },
    data: { type: Array, required: true }
})

const chartData = computed(() => ({
    labels: props.labels,
    datasets: [
        {
            label: 'Wine Categories',
            data: props.data,
            backgroundColor: [
                '#ef4444', '#f59e0b', '#3b82f6', '#10b981', '#8b5cf6'
            ],
            borderWidth: 1,
        },
    ],
}))

const chartOptions = {
    responsive: true,
    plugins: {
        legend: { position: 'bottom' },
    },
}
</script>
