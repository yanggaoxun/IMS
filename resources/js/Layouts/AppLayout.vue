<script setup>
import { computed } from 'vue';
import { pageLabelKeys, useLayout } from './composables/layout';
import { usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppFooter from './AppFooter.vue';
import AppSidebar from './AppSidebar.vue';
import AppTopbar from './AppTopbar.vue';

const { layoutState, hideMobileMenu } = useLayout();
const { t } = useI18n();
const page = usePage();

const containerClass = computed(() => ({
    'layout-mobile-active': layoutState.mobileMenuActive,
    'layout-sidebar-collapsed': layoutState.sidebarCollapsed,
}));

const breadcrumb = computed(() => {
    const keys = pageLabelKeys(page.url);
    return { group: t(keys.group), current: t(keys.current) };
});
</script>

<template>
    <div class="layout-wrapper" :class="containerClass">
        <AppSidebar />
        <main class="layout-content-wrapper">
            <AppTopbar />
            <div class="layout-content-header">
                <i class="pi pi-home"></i>
                <span class="breadcrumb-item">{{ breadcrumb.group }}</span>
                <i class="pi pi-chevron-right !text-xs"></i>
                <span class="breadcrumb-item active">{{ breadcrumb.current }}</span>
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
