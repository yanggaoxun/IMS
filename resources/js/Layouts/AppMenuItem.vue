<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    item: Object,
    index: Number,
});

const page = usePage();

const isActive = computed(() => {
    if (props.item.to) {
        return page.url === props.item.to || page.url.startsWith(props.item.to + '/');
    }
    return false;
});
</script>

<template>
    <li class="layout-menuitem">
        <Link
            v-if="item.to"
            :href="item.to"
            class="layout-menuitem-link"
            :class="{ 'active-route': isActive }"
        >
            <i v-if="item.icon" :class="[item.icon, 'layout-menuitem-icon']"></i>
            <span class="layout-menuitem-text">{{ item.label }}</span>
        </Link>
        <a v-else class="layout-menuitem-link" href="#">
            <i v-if="item.icon" :class="[item.icon, 'layout-menuitem-icon']"></i>
            <span class="layout-menuitem-text">{{ item.label }}</span>
        </a>
    </li>
</template>
