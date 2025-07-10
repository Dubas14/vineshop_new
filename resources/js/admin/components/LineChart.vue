<template>
    <Line :data="chartData" :options="chartOptions" />
</template>

<script setup>
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement,
    Filler
} from 'chart.js'
import { computed } from 'vue'

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler)

const props = defineProps({
    labels: { type: Array, required: true },
    data: { type: Array, required: true },
    datasetLabel: { type: String, default: 'Sales' }
})

const chartData = computed(() => ({
    labels: props.labels,
    datasets: [
        {
            label: props.datasetLabel,
            data: props.data,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            fill: true,
            tension: 0.4
        }
    ]
}))

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false
}
</script>

<style scoped>
canvas {
    max-height: 300px;
}
</style>
