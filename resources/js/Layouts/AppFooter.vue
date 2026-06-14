<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
    <div class="layout-footer">
        {{ t('brand.shortName') }}</div>
</template>
