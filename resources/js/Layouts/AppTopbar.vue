<script setup>
import { computed, ref } from 'vue';
import { pageLabelKeys, useLayout } from './composables/layout';
import { router, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { toggleMenu, toggleDarkMode, isDarkTheme, layoutState } = useLayout();
const { t } = useI18n();
const page = usePage();
const authUser = computed(() => page.props.auth?.user);

const logout = () => {
    router.post('/logout');
};

const userMenuOpen = ref(false);
const notificationsOpen = ref(false);

const pageTitle = computed(() => t(pageLabelKeys(page.url).current));
</script>

<template>
    <header class="layout-topbar">
        <div class="topbar-left">
            <button type="button" class="menu-button menu-button-mobile" @click="toggleMenu">
                <i class="pi pi-bars"></i>
            </button>
            <button type="button" class="menu-button" @click="toggleMenu">
                <i class="pi pi-bars"></i>
            </button>
            <span class="topbar-separator hidden sm:block"></span>
            <span class="page-title hidden sm:block">{{ pageTitle }}</span>
        </div>

        <div class="topbar-right">
            <IconField class="topbar-search hidden sm:flex">
                <InputIcon class="pi pi-search" />
                <InputText :placeholder="t('topbar.search')" />
            </IconField>

            <div class="topbar-actions">
                <button type="button" class="menu-button menu-button-mobile" @click="toggleMenu">
                    <i class="pi pi-bars"></i>
                </button>

                <button type="button" class="app-config-button" @click="toggleDarkMode">
                    <i :class="['pi', isDarkTheme ? 'pi-moon' : 'pi-sun']"></i>
                </button>

                <div class="relative">
                    <Button
                        icon="pi pi-bell"
                        severity="secondary"
                        outlined
                        aria-label="Notifications"
                        @click="notificationsOpen = !notificationsOpen"
                    />
                </div>

                <Button icon="pi pi-cog" severity="secondary" outlined aria-label="Settings" />

                <div class="relative">
                    <Avatar
                        v-if="authUser?.avatar"
                        :image="authUser.avatar"
                        shape="square"
                        class="!w-10 !h-10 !rounded-md !overflow-hidden cursor-pointer"
                        @click="userMenuOpen = !userMenuOpen"
                    />
                    <Button
                        v-else
                        icon="pi pi-user"
                        severity="secondary"
                        outlined
                        aria-label="User"
                        @click="userMenuOpen = !userMenuOpen"
                    />

                    <div
                        v-if="userMenuOpen"
                        class="absolute right-0 top-full mt-2 min-w-[140px] bg-white border border-slate-200 rounded-xl shadow-lg py-2 z-50"
                    >
                        <button
                            type="button"
                            class="w-full text-left px-4 py-2 hover:bg-slate-50 transition-colors flex items-center gap-2 text-red-500"
                            @click="logout"
                        >
                            <i class="pi pi-sign-out"></i>
                            <span>{{ t('topbar.logout') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
