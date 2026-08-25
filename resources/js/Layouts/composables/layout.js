import { computed, reactive } from 'vue';

const layoutConfig = reactive({
    preset: 'Avalon',
    primary: 'blue',
    surface: null,
    darkTheme: false,
    menuMode: 'static',
});

const layoutState = reactive({
    staticMenuInactive: false,
    overlayMenuActive: false,
    mobileMenuActive: false,
    sidebarCollapsed: false,
    profileSidebarVisible: false,
    configSidebarVisible: false,
    sidebarExpanded: false,
    menuHoverActive: false,
    activeMenuItem: null,
    activePath: null,
});

const uikitLabelKeys = {
    formlayout: 'nav.formLayout',
    input: 'nav.input',
    button: 'nav.button',
    table: 'nav.table',
    list: 'nav.list',
    tree: 'nav.tree',
    panels: 'nav.panels',
    overlay: 'nav.overlay',
    media: 'nav.media',
    menu: 'nav.menu',
    messages: 'nav.messages',
    misc: 'nav.misc',
    chart: 'nav.chart',
    timeline: 'nav.timeline',
};

export function pageLabelKeys(url) {
    const segment = url.split('/')[2];
    if (url.startsWith('/uikit/') && uikitLabelKeys[segment]) {
        return { group: 'nav.uikit', current: uikitLabelKeys[segment] };
    }
    return { group: 'nav.dashboards', current: 'nav.dashboard' };
}

export function useLayout() {
    const toggleDarkMode = () => {
        if (!document.startViewTransition) {
            executeDarkModeToggle();
            return;
        }
        document.startViewTransition(() => executeDarkModeToggle());
    };

    const executeDarkModeToggle = () => {
        layoutConfig.darkTheme = !layoutConfig.darkTheme;
        document.documentElement.classList.toggle('app-dark');
    };

    const toggleMenu = () => {
        if (isDesktop()) {
            layoutState.sidebarCollapsed = !layoutState.sidebarCollapsed;
        } else {
            layoutState.mobileMenuActive = !layoutState.mobileMenuActive;
        }
    };

    const hideMobileMenu = () => {
        layoutState.mobileMenuActive = false;
    };

    const isDarkTheme = computed(() => layoutConfig.darkTheme);
    const isDesktop = () => window.innerWidth > 991;
    const hasOpenOverlay = computed(() => layoutState.overlayMenuActive);

    return {
        layoutConfig,
        layoutState,
        isDarkTheme,
        toggleDarkMode,
        toggleMenu,
        hideMobileMenu,
        isDesktop,
        hasOpenOverlay,
    };
}
