<script setup>
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const chartData = ref(null);
const chartOptions = ref(null);

onMounted(() => {
    const style = getComputedStyle(document.documentElement);
    const primary = style.getPropertyValue('--p-primary-500').trim() || '#3b82f6';
    const muted = style.getPropertyValue('--p-text-muted-color').trim() || '#64748b';

    chartData.value = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [
            {
                data: [48000, 22000, 32000, 38000, 45000, 16000, 72000, 45000, 24000, 33000, 86000, 58000],
                backgroundColor: primary,
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 28,
            },
        ],
    };

    chartOptions.value = {
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: { color: muted },
            },
            y: {
                border: { display: false },
                grid: { color: '#eef2f7' },
                ticks: {
                    color: muted,
                    callback: (value) => (value >= 1000 ? `${value / 1000}K` : value),
                },
            },
        },
    };
});
</script>

<template>
    <div class="card h-full !mb-0">
        <div class="card-header">
            <h3>{{ t('dashboard.generalSales') }}</h3>
            <Button icon="pi pi-ellipsis-h" text rounded />
        </div>
        <div class="card-body">
            <div class="flex items-center gap-3">
                <span class="text-3xl font-semibold text-surface-900">$278,942.12</span>
                <span class="bg-green-100 text-green-600 text-xs font-semibold px-2 py-1 rounded-full">12%</span>
            </div>
            <div class="text-surface-500 text-sm mt-1">{{ t('dashboard.from') }} $48,157.94</div>
            <Chart type="bar" :data="chartData" :options="chartOptions" class="h-64 mt-6" />
        </div>
    </div>
</template>
