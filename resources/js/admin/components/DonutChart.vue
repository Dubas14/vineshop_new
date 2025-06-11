<template>
    <div>
        <Doughnut :data="chartData" :options="chartOptions" />
    </div>
</template>

<script setup>
import { Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

ChartJS.register(Title, Tooltip, Legend, ArcElement)

const chartData = {
    labels: [t('red'), t('white'), t('rose'), t('sparkling'), t('dessert')],
    datasets: [
        {
            label: t('wine_categories'),
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
