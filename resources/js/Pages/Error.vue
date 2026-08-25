<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

defineOptions({ layout: AuthLayout });

const props = defineProps({
    status: { type: Number, default: 500 },
});

const { t } = useI18n();

const knownStatuses = [403, 404, 500, 503];
const statusKey = computed(() => (knownStatuses.includes(props.status) ? props.status : 500));
</script>

<template>
    <div class="text-center">
        <div class="text-7xl font-bold text-primary mb-4">{{ statusKey }}</div>
        <h1 class="text-2xl font-semibold text-surface-900 mb-2">{{ t(`errors.${statusKey}.title`) }}</h1>
        <p class="text-surface-500 mb-8">{{ t(`errors.${statusKey}.description`) }}</p>
        <Link href="/">
            <Button :label="t('errors.backHome')" icon="pi pi-home" />
        </Link>
    </div>
</template>
