<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
    <a href="/" class="logo text-surface-900 dark:text-surface-0">
        <svg
            viewBox="0 0 40 40"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            class="logo-svg text-primary-500"
        >
            <path
                d="M20 2 L36 11 L36 29 L20 38 L4 29 L4 11 Z"
                stroke="currentColor"
                stroke-width="2"
                fill="none"
            />
            <path
                d="M20 8 L30 14 L30 26 L20 32 L10 26 L10 14 Z"
                stroke="currentColor"
                stroke-width="1.5"
                fill="currentColor"
                fill-opacity="0.1"
            />
            <circle cx="20" cy="20" r="3" fill="currentColor" />
            <line x1="20" y1="20" x2="20" y2="8" stroke="currentColor" stroke-width="1" />
            <line x1="20" y1="20" x2="30" y2="14" stroke="currentColor" stroke-width="1" />
            <line x1="20" y1="20" x2="30" y2="26" stroke="currentColor" stroke-width="1" />
            <line x1="20" y1="20" x2="20" y2="32" stroke="currentColor" stroke-width="1" />
            <line x1="20" y1="20" x2="10" y2="26" stroke="currentColor" stroke-width="1" />
            <line x1="20" y1="20" x2="10" y2="14" stroke="currentColor" stroke-width="1" />
            <circle cx="20" cy="8" r="1.5" fill="currentColor" />
            <circle cx="30" cy="14" r="1.5" fill="currentColor" />
            <circle cx="30" cy="26" r="1.5" fill="currentColor" />
            <circle cx="20" cy="32" r="1.5" fill="currentColor" />
            <circle cx="10" cy="26" r="1.5" fill="currentColor" />
            <circle cx="10" cy="14" r="1.5" fill="currentColor" />
        </svg>
        <span class="layout-logo-text logo-text">{{ t('brand.name') }}</span>
    </a>
</template>

<style scoped>
.logo {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    white-space: nowrap;
}

.logo-svg {
    width: 1.75rem;
    height: 2rem;
    flex-shrink: 0;
}

.logo-text {
    font-size: 1.25rem;
    font-weight: 700;
    white-space: nowrap;
}

.brand-short {
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    white-space: nowrap;
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .logo-text {
        display: none;
    }
}
</style>
