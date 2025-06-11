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
    PointElement
} from 'chart.js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)

const chartData = {
    labels: [t('mon'), t('tue'), t('wed'), t('thu'), t('fri'), t('sat'), t('sun')],
    datasets: [
        {
            label: t('sales'),
            data: [1200, 1400, 900, 1600, 1100, 1700, 1300],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            fill: true,
            tension: 0.4
        }
    ]
}

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
