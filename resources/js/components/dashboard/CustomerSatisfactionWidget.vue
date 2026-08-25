<script setup>
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const chartData = ref(null);
const chartOptions = ref(null);
const chartPlugins = ref([]);

const heights = [42, 55, 38, 62, 46, 70, 50, 58, 36, 64, 48, 74, 40, 56, 68, 44, 60, 52, 66, 78, 46, 58, 42, 70, 54, 62, 38, 50, 34, 44];

onMounted(() => {
    const style = getComputedStyle(document.documentElement);
    const primary = style.getPropertyValue('--p-primary-500').trim() || '#3b82f6';
    const orange = '#fb923c';
    const gray = '#e2e8f0';

    // 56% blue / 38% orange / 6% gray
    const blueCount = 17;
    const orangeCount = 11;

    chartData.value = {
        labels: heights.map((_, i) => i + 1),
        datasets: [
            {
                data: heights,
                backgroundColor: heights.map((_, i) => (i < blueCount ? primary : i < blueCount + orangeCount ? orange : gray)),
                borderRadius: 3,
                borderSkipped: false,
                barPercentage: 0.9,
                categoryPercentage: 0.55,
            },
        ],
    };

    chartOptions.value = {
        maintainAspectRatio: false,
        layout: { padding: { top: 24 } },
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false },
        },
        scales: {
            x: { display: false },
            y: { display: false },
        },
    };

    const sections = [
        { end: blueCount, text: '56%' },
        { end: blueCount + orangeCount, text: '38%' },
        { end: heights.length, text: '6%' },
    ];

    chartPlugins.value = [
        {
            id: 'sectionLabels',
            afterDatasetsDraw(chart) {
                const { ctx, scales, chartArea } = chart;
                let start = 0;
                ctx.save();
                ctx.fillStyle = '#0f172a';
                ctx.font = '600 13px Archivo, sans-serif';
                ctx.textAlign = 'center';
                sections.forEach(({ end, text }) => {
                    const mid = Math.floor((start + end - 1) / 2);
                    ctx.fillText(text, scales.x.getPixelForValue(mid), chartArea.top - 10);
                    start = end;
                });
                ctx.restore();
            },
        },
    ];
});
</script>

<template>
    <div class="card h-full !mb-0">
        <div class="card-header">
            <h3>{{ t('dashboard.customerSatisfaction') }}</h3>
            <Button icon="pi pi-ellipsis-h" text rounded />
        </div>
        <div class="card-body">
            <div class="flex gap-8">
                <div class="flex items-stretch gap-2">
                    <span class="w-1 rounded-full bg-blue-500"></span>
                    <div>
                        <div class="font-semibold text-surface-900">2,800</div>
                        <div class="text-surface-500 text-sm">{{ t('dashboard.totalCustomers') }}</div>
                    </div>
                </div>
                <div class="flex items-stretch gap-2">
                    <span class="w-1 rounded-full bg-orange-400"></span>
                    <div>
                        <div class="font-semibold text-surface-900">1,900</div>
                        <div class="text-surface-500 text-sm">{{ t('dashboard.paidCustomers') }}</div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-surface-500 text-sm">{{ t('dashboard.weeklyGoal') }}</div>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xl font-semibold text-surface-900">5000</span>
                    <span class="bg-green-100 text-green-600 text-xs font-semibold px-2 py-0.5 rounded-full">12%</span>
                </div>
            </div>
            <Chart type="bar" :data="chartData" :options="chartOptions" :plugins="chartPlugins" class="h-40 mt-4" />
        </div>
    </div>
</template>
