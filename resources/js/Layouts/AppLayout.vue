<script setup>
import { computed } from 'vue';
import { useLayout } from './composables/layout';
import { useI18n } from 'vue-i18n';
import AppFooter from './AppFooter.vue';
import AppSidebar from './AppSidebar.vue';
import AppTopbar from './AppTopbar.vue';

const { layoutState, hideMobileMenu } = useLayout();
const { t } = useI18n();

const containerClass = computed(() => ({
    'layout-mobile-active': layoutState.mobileMenuActive,
    'layout-sidebar-collapsed': layoutState.sidebarCollapsed,
}));
</script>

<template>
    <div class="layout-wrapper" :class="containerClass">
        <AppSidebar />
        <main class="layout-content-wrapper">
            <AppTopbar />
            <div class="layout-content-header">
                <span><i class="pi pi-home text-surface-400"></i></span>
                <div class="flex items-center gap-2 text-surface-400 font-normal">
                    <span class="leading-none">{{ t('nav.dashboards') }}</span>
                    <span><i class="pi pi-chevron-right !text-xs !leading-none"></i></span>
                </div>
                <div class="flex items-center gap-2 text-surface-950 dark:text-surface-0 font-medium">
                    <span class="leading-none">{{ t('nav.dashboard') }}</span>
                </div>
            </div>
            <div class="scrollable-content">
                <div class="layout-main">
                    <slot />
                </div>
                <AppFooter />
            </div>
        </main>
        <div class="layout-mask" @click="hideMobileMenu" />
    </div>
    <Toast />
</template>
