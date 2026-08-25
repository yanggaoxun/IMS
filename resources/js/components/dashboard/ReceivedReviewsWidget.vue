<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const ratings = [
    { stars: 5, count: 514 },
    { stars: 4, count: 420 },
    { stars: 3, count: 230 },
    { stars: 2, count: 120 },
    { stars: 1, count: 10 },
];

const reviews = [
    { name: 'Brook Simmons', initials: 'BS', color: 'bg-blue-100 text-blue-600', textKey: 'dashboard.review1', date: '2024-09-24' },
    { name: 'Annette Black', initials: 'AB', color: 'bg-orange-100 text-orange-600', textKey: 'dashboard.review2', date: '2024-09-24' },
    { name: 'Darlene Robertson', initials: 'DR', color: 'bg-green-100 text-green-600', textKey: 'dashboard.review3', date: '2024-09-23' },
];
</script>

<template>
    <div class="card h-full !mb-0">
        <div class="card-header">
            <h3>{{ t('dashboard.receivedReviews') }}</h3>
            <a class="text-primary text-sm font-medium cursor-pointer hover:underline">{{ t('dashboard.viewAll') }}</a>
        </div>
        <div class="card-body">
            <div class="flex flex-wrap gap-x-4 gap-y-1">
                <span v-for="rating in ratings" :key="rating.stars" class="flex items-center gap-1 text-sm">
                    <i class="pi pi-star-fill text-amber-400 text-xs"></i>
                    <span class="font-medium text-surface-900">{{ rating.stars }}</span>
                    <span class="text-surface-500">({{ rating.count }})</span>
                </span>
            </div>
            <ul class="mt-4 flex flex-col gap-4 list-none p-0 m-0">
                <li v-for="review in reviews" :key="review.name" class="flex gap-3">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0" :class="review.color">
                        {{ review.initials }}
                    </span>
                    <div class="min-w-0">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-medium text-surface-900 text-sm truncate">{{ review.name }}</span>
                            <span class="text-surface-400 text-xs flex-shrink-0">{{ review.date }}</span>
                        </div>
                        <p class="text-surface-500 text-sm m-0 truncate">{{ t(review.textKey) }}</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
