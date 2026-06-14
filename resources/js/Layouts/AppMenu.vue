<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import AppMenuItem from './AppMenuItem.vue';

const { t } = useI18n();

const model = computed(() => [
    {
        label: t('nav.dashboards'),
        icon: 'pi pi-home',
        items: [
            { label: t('nav.dashboard'), icon: 'pi pi-fw pi-warehouse', to: '/' },
        ],
    },
    {
        label: t('nav.uikit'),
        icon: 'pi pi-star-fill',
        items: [
            { label: t('nav.formLayout'), icon: 'pi pi-fw pi-id-card', to: '/uikit/formlayout' },
            { label: t('nav.input'), icon: 'pi pi-fw pi-check-square', to: '/uikit/input' },
            { label: t('nav.button'), icon: 'pi pi-fw pi-box', to: '/uikit/button' },
            { label: t('nav.table'), icon: 'pi pi-fw pi-table', to: '/uikit/table' },
            { label: t('nav.list'), icon: 'pi pi-fw pi-list', to: '/uikit/list' },
            { label: t('nav.tree'), icon: 'pi pi-fw pi-share-alt', to: '/uikit/tree' },
            { label: t('nav.panels'), icon: 'pi pi-fw pi-tablet', to: '/uikit/panels' },
            { label: t('nav.overlay'), icon: 'pi pi-fw pi-clone', to: '/uikit/overlay' },
            { label: t('nav.media'), icon: 'pi pi-fw pi-image', to: '/uikit/media' },
            { label: t('nav.menu'), icon: 'pi pi-fw pi-bars', to: '/uikit/menu' },
            { label: t('nav.messages'), icon: 'pi pi-fw pi-comment', to: '/uikit/messages' },
            { label: t('nav.misc'), icon: 'pi pi-fw pi-circle-off', to: '/uikit/misc' },
            { label: t('nav.chart'), icon: 'pi pi-fw pi-chart-bar', to: '/uikit/chart' },
            { label: t('nav.timeline'), icon: 'pi pi-fw pi-calendar', to: '/uikit/timeline' },
        ],
    },
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(group, gi) in model" :key="gi">
            <li class="layout-root-menuitem">
                <div class="layout-menuitem-root-text">{{ group.label }}</div>
                <a class="layout-menuitem-link" tabindex="0">
                    <i :class="[group.icon, 'layout-menuitem-icon']"></i>
                    <span class="layout-menuitem-text">{{ group.label }}</span>
                    <i class="pi pi-fw pi-angle-down layout-submenu-toggler"></i>
                </a>
                <ul class="layout-root-submenulist">
                    <app-menu-item
                        v-for="(item, ii) in group.items"
                        :key="`${gi}-${ii}`"
                        :item="item"
                        :index="ii"
                    />
                </ul>
            </li>
            <li v-if="gi < model.length - 1" class="menu-separator"></li>
        </template>
    </ul>
</template>
