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
