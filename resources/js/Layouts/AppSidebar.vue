<script setup>
import { computed } from 'vue';
import AppMenu from './AppMenu.vue';
import AppLogo from '@/components/AppLogo.vue';
import { useLayout } from './composables/layout';

const { layoutState } = useLayout();

const sidebarClass = computed(() => ({
    'layout-sidebar-collapsed': layoutState.sidebarCollapsed,
}));
</script>

<template>
    <div class="layout-sidebar" :class="sidebarClass">
        <div class="sidebar-header">
            <AppLogo />
        </div>
        <AppMenu />
    </div>
</template>
