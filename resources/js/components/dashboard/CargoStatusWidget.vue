<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const stops = [
    { date: 'Sep 24', time: '10:00 AM', line1: '123 Main Street, Anytown', line2: 'CA 12345' },
    { date: 'Sep 24', time: '10:00 AM', line1: '456 Oak Drive, Springfield', line2: 'TX 78910' },
];
</script>

<template>
    <div class="card h-full !mb-0">
        <div class="card-header">
            <h3>{{ t('dashboard.cargoStatus') }}</h3>
            <a class="text-primary text-sm font-medium cursor-pointer hover:underline">{{ t('dashboard.viewAll') }}</a>
        </div>
        <div class="card-body">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-10 h-10 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center flex-shrink-0">
                        <i class="pi pi-desktop text-surface-500"></i>
                    </span>
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-surface-900 truncate">Macbook Air 14inch 8GB Ram 256GB</div>
                        <div class="text-xs text-surface-500">#12545</div>
                    </div>
                </div>
                <Tag :value="t('dashboard.sent')" severity="success" class="flex-shrink-0" />
            </div>
            <ul class="cargo-timeline mt-5 flex flex-col list-none p-0 m-0">
                <li v-for="(stop, index) in stops" :key="index" class="flex gap-3">
                    <div class="w-16 flex-shrink-0">
                        <div class="text-sm font-medium text-surface-900">{{ stop.date }}</div>
                        <div class="text-xs text-surface-500">{{ stop.time }}</div>
                    </div>
                    <div class="cargo-marker" :class="{ 'cargo-last': index === stops.length - 1 }"></div>
                    <div class="min-w-0 pb-5">
                        <div class="text-sm text-surface-700">{{ stop.line1 }}</div>
                        <div class="text-sm text-surface-500">{{ stop.line2 }}</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
.cargo-marker {
    position: relative;
    width: 0.625rem;
    flex-shrink: 0;
}

.cargo-marker::before {
    content: '';
    position: absolute;
    top: 0.375rem;
    left: 0;
    width: 0.625rem;
    height: 0.625rem;
    border-radius: 9999px;
    border: 2px solid var(--p-primary-500);
    background: #fff;
}

.cargo-marker::after {
    content: '';
    position: absolute;
    top: 1.125rem;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    height: calc(100% - 1rem);
    background: #e2e8f0;
}

.cargo-marker.cargo-last::after {
    display: none;
}
</style>
