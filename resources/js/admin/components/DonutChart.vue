<template>
    <div>
        <Doughnut :data="chartData" :options="chartOptions" />
    </div>
</template>

<script setup>
import { Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement)

const chartData = {
    labels: ['Червоне', 'Біле', 'Рожеве', 'Ігристе', 'Десертне'],
    datasets: [
        {
            label: 'Категорії вин',
            data: [35, 25, 15, 15, 10],
            backgroundColor: [
                '#ef4444',
                '#f59e0b',
                '#3b82f6',
                '#10b981',
                '#8b5cf6',
            ],
            borderWidth: 1,
        },
    ],
}

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'bottom',
        },
    },
}
</script>
