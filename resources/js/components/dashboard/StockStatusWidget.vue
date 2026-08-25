<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const products = [
    { name: 'Macbook M2 Pro 14inch 16GB Ram 512GB', stock: '12,864/20,000', percent: 64, color: 'bg-blue-500' },
    { name: 'Macbook Air 14inch 8GB Ram 256GB', stock: '3,425/20,000', percent: 17, color: 'bg-cyan-400' },
    { name: 'iPad Pro 11inch 128GB', stock: '8,210/20,000', percent: 41, color: 'bg-orange-400' },
];
</script>

<template>
    <div class="card h-full !mb-0">
        <div class="card-header">
            <h3>{{ t('dashboard.stockStatus') }}</h3>
            <a class="text-primary text-sm font-medium cursor-pointer hover:underline">{{ t('dashboard.viewAll') }}</a>
        </div>
        <div class="card-body">
            <div class="flex items-center justify-between text-sm">
                <span class="text-surface-500">{{ t('dashboard.productStock') }}</span>
                <span class="font-medium text-surface-900">64,344/100,000</span>
            </div>
            <div class="flex h-2 mt-2 rounded-full overflow-hidden gap-0.5">
                <div class="bg-blue-500 rounded-full" style="width: 34%"></div>
                <div class="bg-cyan-400 rounded-full" style="width: 18%"></div>
                <div class="bg-orange-400 rounded-full" style="width: 12%"></div>
                <div class="bg-slate-200 rounded-full flex-1"></div>
            </div>
            <ul class="mt-5 flex flex-col gap-4 list-none p-0 m-0">
                <li v-for="product in products" :key="product.name">
                    <div class="flex items-center justify-between gap-3 text-sm">
                        <span class="flex items-center gap-2 min-w-0">
                            <span class="w-2 h-2 rounded-full flex-shrink-0" :class="product.color"></span>
                            <span class="truncate text-surface-700">{{ product.name }}</span>
                        </span>
                        <span class="font-medium text-surface-900 flex-shrink-0">{{ product.stock }}</span>
                    </div>
                    <div class="h-1.5 bg-slate-100 rounded-full mt-1.5">
                        <div class="h-full rounded-full" :class="product.color" :style="{ width: product.percent + '%' }"></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
