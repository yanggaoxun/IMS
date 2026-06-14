# Avalon UI 风格迁移实现计划

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** 将现有 Laravel + Inertia + Vue3 + PrimeVue 项目从 Sakai 主题整体迁移为 Avalon 风格的布局与视觉系统。

**Architecture:** 保留技术栈与页面组件结构，替换 Sakai 的 layout 样式与布局组件为 Avalon 风格：左侧分组图标边栏、顶部通栏、浅蓝灰背景、圆角白卡、蓝色主色。通过 PrimeVue `definePreset(Aura, {...})` 覆盖主题色板，并重建 layout SCSS 与 Vue 布局组件。

**Tech Stack:** Laravel 12, Inertia.js Vue3, PrimeVue 4.5.5, `@primeuix/themes` Aura, Tailwind CSS 4, Sass, Vue I18n

---

## File Structure

| File | Responsibility |
|------|----------------|
| `resources/js/app.js` | 应用入口，注册 PrimeVue 并引入自定义主题 preset |
| `resources/js/theme/avalon-preset.js` | **新建** — 基于 Aura 覆盖 primary/surface 色板为 Avalon 蓝色调 |
| `resources/css/app.css` | 引入 Tailwind、Archivo 字体、primeicons |
| `resources/css/avalon/layout/layout.scss` | **新建** — Avalon 布局主样式（替代 `resources/css/sakai/layout/layout.scss`） |
| `resources/css/avalon/layout/_variables.scss` | **新建** — Avalon CSS 变量（背景、边栏宽度、顶栏高度等） |
| `resources/css/avalon/layout/_sidebar.scss` | **新建** — 左侧边栏样式 |
| `resources/css/avalon/layout/_topbar.scss` | **新建** — 顶部栏样式 |
| `resources/css/avalon/layout/_main.scss` | **新建** — 主内容区背景与卡片样式 |
| `resources/css/avalon/layout/_responsive.scss` | **新建** — 响应式规则 |
| `resources/js/Layouts/AppLayout.vue` | 修改 — 使用新的 Avalon layout class |
| `resources/js/Layouts/AppSidebar.vue` | 重写 — 分组图标菜单边栏 |
| `resources/js/Layouts/AppMenu.vue` | 重写 — 菜单数据与分组渲染 |
| `resources/js/Layouts/AppMenuItem.vue` | 重写 — 单个菜单项，active 蓝色高亮 |
| `resources/js/Layouts/AppTopbar.vue` | 重写 — 汉堡菜单、页面标题、搜索、通知、用户头像 |
| `resources/js/Layouts/AuthLayout.vue` | 修改 — 登录页外层背景改为 Avalon 浅蓝灰 |
| `resources/js/Pages/Auth/Login.vue` | 重写 — 圆角白卡、蓝色主按钮 |
| `resources/js/Pages/Dashboard.vue` | 修改 — 间距与卡片容器 |
| `resources/js/components/dashboard/*.vue` | 修改 — 卡片样式改为 Avalon 圆角白卡 |
| `resources/js/Pages/Uikit/FormLayout.vue` | 重写 — 按 Avalon FormLayout 示例结构 |
| `resources/js/Pages/Uikit/*.vue` | 修改 — 统一使用 `.card` 白卡容器 |
| `resources/js/locales/zh.js` | 修改 — 补充 Avalon 风格所需的菜单/登录文案 |
| `resources/js/locales/en.js` | 修改 — 补充英文文案 |
| `vite.config.js` | 修改 — 添加 `@` alias 指向 `resources/js`（若不存在） |

---

## Task 1: 创建 Avalon 主题 Preset

**Files:**
- Create: `resources/js/theme/avalon-preset.js`
- Modify: `resources/js/app.js`

- [ ] **Step 1: 新建 Avalon preset 文件**

```javascript
import { definePreset } from '@primeuix/themes';
import Aura from '@primeuix/themes/aura';

export const AvalonPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{blue.50}',
            100: '{blue.100}',
            200: '{blue.200}',
            300: '{blue.300}',
            400: '{blue.400}',
            500: '{blue.500}',
            600: '{blue.600}',
            700: '{blue.700}',
            800: '{blue.800}',
            900: '{blue.900}',
            950: '{blue.950}',
        },
        colorScheme: {
            light: {
                surface: {
                    0: '#ffffff',
                    50: '{slate.50}',
                    100: '{slate.100}',
                    200: '{slate.200}',
                    300: '{slate.300}',
                    400: '{slate.400}',
                    500: '{slate.500}',
                    600: '{slate.600}',
                    700: '{slate.700}',
                    800: '{slate.800}',
                    900: '{slate.900}',
                    950: '{slate.950}',
                },
                primary: {
                    color: '{primary.500}',
                    contrastColor: '#ffffff',
                    hoverColor: '{primary.600}',
                    activeColor: '{primary.700}',
                },
                content: {
                    background: '#ffffff',
                    hoverBackground: '{slate.100}',
                    borderColor: '{slate.200}',
                    color: '{slate.700}',
                    hoverColor: '{slate.900}',
                },
            },
            dark: {
                surface: {
                    0: '#ffffff',
                    50: '{slate.50}',
                    100: '{slate.100}',
                    200: '{slate.200}',
                    300: '{slate.300}',
                    400: '{slate.400}',
                    500: '{slate.500}',
                    600: '{slate.600}',
                    700: '{slate.700}',
                    800: '{slate.800}',
                    900: '{slate.900}',
                    950: '{slate.950}',
                },
                content: {
                    background: '{slate.900}',
                    hoverBackground: '{slate.800}',
                    borderColor: '{slate.700}',
                    color: '{slate.200}',
                    hoverColor: '{slate.0}',
                },
            },
        },
    },
});
```

- [ ] **Step 2: 在 app.js 中使用 AvalonPreset**

修改 `resources/js/app.js`：

```javascript
import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import PrimeVue from 'primevue/config';
import { AvalonPreset } from './theme/avalon-preset';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import Toast from 'primevue/toast';
import StyleClass from 'primevue/styleclass';
import AppLayout from './Layouts/AppLayout.vue';
import i18n from './locales';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} — ${appName}`,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        );

        page.then((module) => {
            module.default.layout = module.default.layout || AppLayout;
        });

        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(PrimeVue, {
                theme: {
                    preset: AvalonPreset,
                    options: {
                        darkModeSelector: '.app-dark',
                    },
                },
            })
            .use(ToastService)
            .use(ConfirmationService)
            .component('Toast', Toast)
            .directive('styleclass', StyleClass);

        return app.mount(el);
    },
});
```

- [ ] **Step 3: 更新 app.css 引入 Archivo 字体**

修改 `resources/css/app.css`：

```css
@import url('https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&display=swap');
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';
@source '../**/*.vue';

@theme {
    --font-sans: 'Archivo', ui-sans-serif, system-ui, sans-serif;
    --color-primary-50: #eff6ff;
    --color-primary-100: #dbeafe;
    --color-primary-200: #bfdbfe;
    --color-primary-300: #93c5fd;
    --color-primary-400: #60a5fa;
    --color-primary-500: #3b82f6;
    --color-primary-600: #2563eb;
    --color-primary-700: #1d4ed8;
    --color-primary-800: #1e40af;
    --color-primary-900: #1e3a8a;
    --color-primary-950: #172554;
    --color-surface-ground: #f3f8ff;
}

@import 'primeicons/primeicons.css';
```

- [ ] **Step 4: Commit**

```bash
git add resources/js/app.js resources/css/app.css resources/js/theme/avalon-preset.js
git commit -m "feat(theme): add Avalon blue preset and Archivo font"
```

---

## Task 2: 创建 Avalon 布局 SCSS

**Files:**
- Create: `resources/css/avalon/layout/_variables.scss`
- Create: `resources/css/avalon/layout/_core.scss`
- Create: `resources/css/avalon/layout/_sidebar.scss`
- Create: `resources/css/avalon/layout/_topbar.scss`
- Create: `resources/css/avalon/layout/_main.scss`
- Create: `resources/css/avalon/layout/_responsive.scss`
- Create: `resources/css/avalon/layout/layout.scss`
- Modify: `resources/css/app.css`（移除 Sakai import）

- [ ] **Step 1: 创建变量文件**

`resources/css/avalon/layout/_variables.scss`：

```scss
:root {
    --avalon-sidebar-width: 18rem;
    --avalon-sidebar-collapsed-width: 5rem;
    --avalon-topbar-height: 4.5rem;
    --avalon-content-bg: #f3f8ff;
    --avalon-card-bg: #ffffff;
    --avalon-card-radius: 1.25rem;
    --avalon-card-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
    --avalon-border-color: #e2e8f0;
    --avalon-text-muted: #64748b;
    --avalon-menu-active-bg: #eff6ff;
    --avalon-menu-active-color: #2563eb;
    --avalon-menu-group-color: #94a3b8;
}
```

- [ ] **Step 2: 创建核心样式**

`resources/css/avalon/layout/_core.scss`：

```scss
html {
    height: 100%;
    font-size: 14px;
    line-height: 1.5;
}

body {
    font-family: 'Archivo', sans-serif;
    color: var(--p-text-color);
    background-color: var(--avalon-content-bg);
    margin: 0;
    padding: 0;
    min-height: 100%;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

a {
    text-decoration: none;
    color: inherit;
}

.layout-wrapper {
    min-height: 100vh;
}
```

- [ ] **Step 3: 创建主内容区样式**

`resources/css/avalon/layout/_main.scss`：

```scss
.layout-main-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin-left: var(--avalon-sidebar-width);
    padding: calc(var(--avalon-topbar-height) + 1.5rem) 1.5rem 1.5rem;
    transition: margin-left 0.2s ease;
}

.layout-main {
    flex: 1 1 auto;
}

.card {
    background: var(--avalon-card-bg);
    border-radius: var(--avalon-card-radius);
    box-shadow: var(--avalon-card-shadow);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
```

- [ ] **Step 4: 创建边栏样式**

`resources/css/avalon/layout/_sidebar.scss`：

```scss
.layout-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--avalon-sidebar-width);
    height: 100vh;
    background: var(--avalon-card-bg);
    border-right: 1px solid var(--avalon-border-color);
    z-index: 50;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease, width 0.2s ease;
}

.layout-sidebar-header {
    height: var(--avalon-topbar-height);
    display: flex;
    align-items: center;
    padding: 0 1.5rem;
    border-bottom: 1px solid var(--avalon-border-color);
}

.layout-logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--p-text-color);
}

.layout-menu {
    list-style: none;
    margin: 0;
    padding: 1rem 0;
    overflow-y: auto;
    flex: 1;
}

.layout-menu-group {
    padding: 0 1.25rem;
    margin: 1rem 0 0.5rem;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--avalon-menu-group-color);
}

.layout-menu-item {
    margin: 0.25rem 0.75rem;
}

.layout-menu-item-link {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    color: var(--p-text-color);
    font-weight: 500;
    transition: background 0.15s ease, color 0.15s ease;
}

.layout-menu-item-link:hover {
    background: var(--avalon-menu-active-bg);
    color: var(--avalon-menu-active-color);
}

.layout-menu-item-link.active {
    background: var(--avalon-menu-active-bg);
    color: var(--avalon-menu-active-color);
}

.layout-menu-item-icon {
    font-size: 1.125rem;
    width: 1.5rem;
    text-align: center;
}
```

- [ ] **Step 5: 创建顶栏样式**

`resources/css/avalon/layout/_topbar.scss`：

```scss
.layout-topbar {
    position: fixed;
    top: 0;
    left: var(--avalon-sidebar-width);
    right: 0;
    height: var(--avalon-topbar-height);
    background: var(--avalon-card-bg);
    border-bottom: 1px solid var(--avalon-border-color);
    z-index: 40;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.5rem;
    transition: left 0.2s ease;
}

.layout-topbar-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.layout-menu-button {
    display: none;
    width: 2.5rem;
    height: 2.5rem;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    border: 1px solid var(--avalon-border-color);
    background: transparent;
    color: var(--p-text-color);
    cursor: pointer;
}

.layout-topbar-title {
    font-size: 1.125rem;
    font-weight: 600;
}

.layout-topbar-right {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.layout-topbar-action {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    border: none;
    background: transparent;
    color: var(--p-text-color);
    cursor: pointer;
    transition: background 0.15s ease;
}

.layout-topbar-action:hover {
    background: var(--avalon-menu-active-bg);
    color: var(--avalon-menu-active-color);
}

.layout-topbar-avatar {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
}

.layout-topbar-search {
    position: relative;
    display: flex;
    align-items: center;
}

.layout-topbar-search input {
    width: 16rem;
    padding: 0.5rem 0.75rem 0.5rem 2.25rem;
    border-radius: 0.75rem;
    border: 1px solid var(--avalon-border-color);
    background: var(--avalon-content-bg);
    font-size: 0.875rem;
    outline: none;
}

.layout-topbar-search i {
    position: absolute;
    left: 0.75rem;
    color: var(--avalon-text-muted);
}
```

- [ ] **Step 6: 创建响应式样式**

`resources/css/avalon/layout/_responsive.scss`：

```scss
@media (max-width: 991px) {
    .layout-sidebar {
        transform: translateX(-100%);
    }

    .layout-wrapper.layout-mobile-active .layout-sidebar {
        transform: translateX(0);
    }

    .layout-main-container {
        margin-left: 0;
    }

    .layout-topbar {
        left: 0;
    }

    .layout-menu-button {
        display: flex;
    }

    .layout-mask {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 45;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }

    .layout-wrapper.layout-mobile-active .layout-mask {
        opacity: 1;
        pointer-events: auto;
    }
}

@media (max-width: 576px) {
    .layout-topbar-search {
        display: none;
    }

    .layout-main-container {
        padding: calc(var(--avalon-topbar-height) + 1rem) 1rem 1rem;
    }
}
```

- [ ] **Step 7: 创建布局入口文件**

`resources/css/avalon/layout/layout.scss`：

```scss
@use './variables';
@use './core';
@use './sidebar';
@use './topbar';
@use './main';
@use './responsive';
```

- [ ] **Step 8: 在 app.css 中引入新布局 SCSS**

在 `resources/css/app.css` 末尾添加：

```css
@import './avalon/layout/layout.scss';
```

并移除所有 `resources/css/sakai/**` 的 import。

- [ ] **Step 9: Commit**

```bash
git add resources/css/avalon resources/css/app.css resources/js/app.js
git commit -m "feat(layout): add Avalon layout styles and replace Sakai theme"
```

---

## Task 3: 重建布局组件

**Files:**
- Modify: `resources/js/Layouts/AppLayout.vue`
- Rewrite: `resources/js/Layouts/AppSidebar.vue`
- Rewrite: `resources/js/Layouts/AppMenu.vue`
- Rewrite: `resources/js/Layouts/AppMenuItem.vue`
- Rewrite: `resources/js/Layouts/AppTopbar.vue`
- Modify: `resources/js/Layouts/composables/layout.js`

- [ ] **Step 1: 更新 layout composable**

修改 `resources/js/Layouts/composables/layout.js`，确保 `mobileMenuActive` 存在且 `isDesktop` 断点为 991px：

```javascript
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
    profileSidebarVisible: false,
    configSidebarVisible: false,
    sidebarExpanded: false,
    menuHoverActive: false,
    activeMenuItem: null,
    activePath: null,
});

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
            if (layoutConfig.menuMode === 'static') {
                layoutState.staticMenuInactive = !layoutState.staticMenuInactive;
            }
            if (layoutConfig.menuMode === 'overlay') {
                layoutState.overlayMenuActive = !layoutState.overlayMenuActive;
            }
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
```

- [ ] **Step 2: 重写 AppMenuItem.vue**

`resources/js/Layouts/AppMenuItem.vue`：

```vue
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
    <li class="layout-menu-item">
        <Link
            v-if="item.to"
            :href="item.to"
            class="layout-menu-item-link"
            :class="{ active: isActive }"
        >
            <i v-if="item.icon" :class="[item.icon, 'layout-menu-item-icon']"></i>
            <span>{{ item.label }}</span>
        </Link>
        <a v-else class="layout-menu-item-link" href="#">
            <i v-if="item.icon" :class="[item.icon, 'layout-menu-item-icon']"></i>
            <span>{{ item.label }}</span>
        </a>
    </li>
</template>
```

- [ ] **Step 3: 重写 AppMenu.vue**

`resources/js/Layouts/AppMenu.vue`：

```vue
<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import AppMenuItem from './AppMenuItem.vue';

const { t } = useI18n();

const model = computed(() => [
    {
        label: t('nav.dashboards'),
        items: [
            { label: t('nav.dashboard'), icon: 'pi pi-fw pi-home', to: '/' },
        ],
    },
    {
        label: t('nav.uikit'),
        items: [
            { label: t('nav.formLayout'), icon: 'pi pi-fw pi-id-card', to: '/uikit/formlayout' },
            { label: t('nav.input'), icon: 'pi pi-fw pi-check-square', to: '/uikit/input' },
            { label: t('nav.button'), icon: 'pi pi-fw pi-mobile', to: '/uikit/button' },
            { label: t('nav.table'), icon: 'pi pi-fw pi-table', to: '/uikit/table' },
            { label: t('nav.list'), icon: 'pi pi-fw pi-list', to: '/uikit/list' },
            { label: t('nav.chart'), icon: 'pi pi-fw pi-chart-bar', to: '/uikit/chart' },
            { label: t('nav.media'), icon: 'pi pi-fw pi-image', to: '/uikit/media' },
            { label: t('nav.menu'), icon: 'pi pi-fw pi-bars', to: '/uikit/menu' },
            { label: t('nav.messages'), icon: 'pi pi-fw pi-comment', to: '/uikit/messages' },
            { label: t('nav.overlay'), icon: 'pi pi-fw pi-clone', to: '/uikit/overlay' },
            { label: t('nav.panels'), icon: 'pi pi-fw pi-th-large', to: '/uikit/panels' },
            { label: t('nav.misc'), icon: 'pi pi-fw pi-circle-off', to: '/uikit/misc' },
            { label: t('nav.timeline'), icon: 'pi pi-fw pi-calendar', to: '/uikit/timeline' },
            { label: t('nav.tree'), icon: 'pi pi-fw pi-share-alt', to: '/uikit/tree' },
        ],
    },
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(group, gi) in model" :key="gi">
            <li class="layout-menu-group">{{ group.label }}</li>
            <app-menu-item
                v-for="(item, ii) in group.items"
                :key="`${gi}-${ii}`"
                :item="item"
                :index="ii"
            />
        </template>
    </ul>
</template>
```

- [ ] **Step 4: 重写 AppSidebar.vue**

`resources/js/Layouts/AppSidebar.vue`：

```vue
<script setup>
import AppMenu from './AppMenu.vue';
import AppLogo from '@/components/AppLogo.vue';
</script>

<template>
    <div class="layout-sidebar">
        <div class="layout-sidebar-header">
            <AppLogo />
        </div>
        <AppMenu />
    </div>
</template>
```

- [ ] **Step 5: 重写 AppTopbar.vue**

`resources/js/Layouts/AppTopbar.vue`：

```vue
<script setup>
import { computed, ref } from 'vue';
import { useLayout } from './composables/layout';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { toggleMenu, toggleDarkMode, isDarkTheme } = useLayout();
const { locale, t } = useI18n();
const page = usePage();
const authUser = computed(() => page.props.auth?.user);

const logout = () => {
    router.post('/logout');
};

const languages = [
    { code: 'zh', label: '中文', short: '中' },
    { code: 'en', label: 'English', short: 'EN' },
];

const currentLang = ref(locale.value);
const userMenuOpen = ref(false);

const switchLanguage = (lang) => {
    currentLang.value = lang;
    locale.value = lang;
    localStorage.setItem('locale', lang);
    router.post('/locale', { locale: lang }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => window.location.reload(),
    });
};
</script>

<template>
    <div class="layout-topbar">
        <div class="layout-topbar-left">
            <button type="button" class="layout-menu-button" @click="toggleMenu">
                <i class="pi pi-bars"></i>
            </button>
            <span class="layout-topbar-title">{{ t('topbar.title') }}</span>
        </div>

        <div class="layout-topbar-right">
            <div class="layout-topbar-search hidden sm:flex">
                <i class="pi pi-search"></i>
                <input type="text" :placeholder="t('topbar.search')" />
            </div>

            <button type="button" class="layout-topbar-action" @click="toggleDarkMode">
                <i :class="['pi', isDarkTheme ? 'pi-moon' : 'pi-sun']"></i>
            </button>

            <button type="button" class="layout-topbar-action">
                <i class="pi pi-bell"></i>
            </button>

            <button type="button" class="layout-topbar-action">
                <i class="pi pi-cog"></i>
            </button>

            <div class="relative">
                <img
                    v-if="authUser?.avatar"
                    :src="authUser.avatar"
                    class="layout-topbar-avatar"
                    @click="userMenuOpen = !userMenuOpen"
                    alt="avatar"
                />
                <button
                    v-else
                    type="button"
                    class="layout-topbar-action"
                    @click="userMenuOpen = !userMenuOpen"
                >
                    <i class="pi pi-user"></i>
                </button>

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
</template>
```

- [ ] **Step 6: 更新 AppLayout.vue**

`resources/js/Layouts/AppLayout.vue`：

```vue
<script setup>
import { computed } from 'vue';
import { useLayout } from './composables/layout';
import AppFooter from './AppFooter.vue';
import AppSidebar from './AppSidebar.vue';
import AppTopbar from './AppTopbar.vue';

const { layoutState, hideMobileMenu } = useLayout();

const containerClass = computed(() => ({
    'layout-mobile-active': layoutState.mobileMenuActive,
}));
</script>

<template>
    <div class="layout-wrapper" :class="containerClass">
        <AppTopbar />
        <AppSidebar />
        <div class="layout-main-container">
            <div class="layout-main">
                <slot />
            </div>
            <AppFooter />
        </div>
        <div class="layout-mask" @click="hideMobileMenu" />
    </div>
    <Toast />
</template>
```

- [ ] **Step 7: Commit**

```bash
git add resources/js/Layouts
git commit -m "feat(layout): rebuild AppSidebar, AppTopbar, AppMenu in Avalon style"
```

---

## Task 4: 补充多语言文案

**Files:**
- Modify: `resources/js/locales/zh.js`
- Modify: `resources/js/locales/en.js`

- [ ] **Step 1: 更新中文文案**

在 `resources/js/locales/zh.js` 中确保包含以下键：

```javascript
export default {
    // ...existing
    nav: {
        dashboards: '仪表盘',
        dashboard: '总览',
        uikit: 'UI 组件',
        formLayout: '表单布局',
        input: '输入框',
        button: '按钮',
        table: '表格',
        list: '列表',
        chart: '图表',
        media: '媒体',
        menu: '菜单',
        messages: '消息',
        overlay: '浮层',
        panels: '面板',
        misc: '其他',
        timeline: '时间线',
        tree: '树形',
    },
    topbar: {
        title: '智能方舱管理系统',
        search: '搜索...',
        logout: '退出登录',
    },
};
```

- [ ] **Step 2: 更新英文文案**

在 `resources/js/locales/en.js` 中同步添加对应英文键。

- [ ] **Step 3: Commit**

```bash
git add resources/js/locales
git commit -m "feat(i18n): add Avalon navigation and topbar translations"
```

---

## Task 5: 重写登录页

**Files:**
- Modify: `resources/js/Pages/Auth/Login.vue`
- Modify: `resources/js/Layouts/AuthLayout.vue`

- [ ] **Step 1: 更新 AuthLayout.vue**

`resources/js/Layouts/AuthLayout.vue`：

```vue
<template>
    <div class="min-h-screen flex items-center justify-center bg-[#f3f8ff] px-4">
        <slot />
    </div>
</template>
```

- [ ] **Step 2: 重写 Login.vue**

`resources/js/Pages/Auth/Login.vue`：

```vue
<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AppLogo from '@/Components/AppLogo.vue';
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

defineOptions({
    layout: AuthLayout,
});

const { t } = useI18n();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/auth/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="w-full max-w-md">
        <div class="card flex flex-col items-center">
            <div class="mb-8 flex justify-center">
                <AppLogo />
            </div>

            <div class="text-center mb-8">
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-white mb-2">
                    {{ t('login.welcome') }}
                </h1>
                <p class="text-slate-500">{{ t('login.signInToContinue') }}</p>
            </div>

            <form @submit.prevent="submit" class="w-full">
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col gap-2">
                        <label for="email" class="font-medium text-slate-700">{{ t('login.email') }}</label>
                        <InputText
                            id="email"
                            v-model="form.email"
                            type="text"
                            :placeholder="t('login.emailPlaceholder')"
                            class="w-full"
                            :class="{ 'p-invalid': form.errors.email }"
                        />
                        <small v-if="form.errors.email" class="text-red-500">{{ form.errors.email }}</small>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="password" class="font-medium text-slate-700">{{ t('login.password') }}</label>
                        <Password
                            id="password"
                            v-model="form.password"
                            :placeholder="t('login.passwordPlaceholder')"
                            :toggleMask="true"
                            :feedback="false"
                            fluid
                            :class="{ 'p-invalid': form.errors.password }"
                        />
                        <small v-if="form.errors.password" class="text-red-500">{{ form.errors.password }}</small>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Checkbox v-model="form.remember" id="rememberme" :binary="true" />
                            <label for="rememberme" class="text-sm text-slate-600">{{ t('login.rememberMe') }}</label>
                        </div>
                        <span class="text-sm font-medium text-primary cursor-pointer">{{ t('login.forgotPassword') }}</span>
                    </div>

                    <Button type="submit" :label="t('login.signIn')" class="w-full" :loading="form.processing" />
                </div>
            </form>
        </div>
    </div>
</template>
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/Pages/Auth/Login.vue resources/js/Layouts/AuthLayout.vue
git commit -m "feat(auth): rewrite login page in Avalon style"
```

---

## Task 6: 更新 Dashboard 与 Widgets

**Files:**
- Modify: `resources/js/Pages/Dashboard.vue`
- Modify: `resources/js/components/dashboard/StatsWidget.vue`
- Modify: `resources/js/components/dashboard/RecentSalesWidget.vue`
- Modify: `resources/js/components/dashboard/BestSellingWidget.vue`
- Modify: `resources/js/components/dashboard/RevenueStreamWidget.vue`
- Modify: `resources/js/components/dashboard/NotificationsWidget.vue`

- [ ] **Step 1: 更新 Dashboard.vue**

`resources/js/Pages/Dashboard.vue`：

```vue
<script setup>
import BestSellingWidget from '@/components/dashboard/BestSellingWidget.vue';
import NotificationsWidget from '@/components/dashboard/NotificationsWidget.vue';
import RecentSalesWidget from '@/components/dashboard/RecentSalesWidget.vue';
import RevenueStreamWidget from '@/components/dashboard/RevenueStreamWidget.vue';
import StatsWidget from '@/components/dashboard/StatsWidget.vue';
</script>

<template>
    <div class="grid grid-cols-12 gap-6">
        <StatsWidget />
        <div class="col-span-12 xl:col-span-6 flex flex-col gap-6">
            <RecentSalesWidget />
            <BestSellingWidget />
        </div>
        <div class="col-span-12 xl:col-span-6 flex flex-col gap-6">
            <RevenueStreamWidget />
            <NotificationsWidget />
        </div>
    </div>
</template>
```

- [ ] **Step 2: 给所有 dashboard widgets 外层加 `.card` 并调整标题栏**

例如 `StatsWidget.vue` 应该输出 `.card` 容器。其他 widget 同样处理，确保每个 widget 的 `<template>` 根节点为：

```vue
<template>
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-lg">{{ title }}</h3>
            <Button icon="pi pi-ellipsis-h" text rounded />
        </div>
        <!-- content -->
    </div>
</template>
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/Pages/Dashboard.vue resources/js/components/dashboard
git commit -m "feat(dashboard): update dashboard widgets to Avalon card style"
```

---

## Task 7: 重写 FormLayout 页面

**Files:**
- Modify: `resources/js/Pages/Uikit/FormLayout.vue`

- [ ] **Step 1: 按 Avalon 风格重写**

`resources/js/Pages/Uikit/FormLayout.vue`：

```vue
<script setup>
import { ref } from 'vue';

const dropdownItems = ref([
    { name: 'Option 1', code: 'Option 1' },
    { name: 'Option 2', code: 'Option 2' },
    { name: 'Option 3', code: 'Option 3' },
]);

const dropdownItem = ref(null);
</script>

<template>
    <Fluid>
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-6 flex flex-col gap-6">
                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Vertical</h3>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="name1">Name</label>
                            <InputText id="name1" type="text" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="email1">Email</label>
                            <InputText id="email1" type="text" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="age1">Age</label>
                            <InputText id="age1" type="text" />
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Vertical Grid</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="name2">Name</label>
                            <InputText id="name2" type="text" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="email2">Email</label>
                            <InputText id="email2" type="text" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-6 flex flex-col gap-6">
                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Horizontal</h3>
                    <div class="flex flex-col gap-4">
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <label for="name3" class="col-span-12 md:col-span-2">Name</label>
                            <div class="col-span-12 md:col-span-10">
                                <InputText id="name3" type="text" />
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <label for="email3" class="col-span-12 md:col-span-2">Email</label>
                            <div class="col-span-12 md:col-span-10">
                                <InputText id="email3" type="text" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Inline</h3>
                    <div class="flex flex-wrap items-end gap-4">
                        <div>
                            <label for="firstname1" class="sr-only">Firstname</label>
                            <InputText id="firstname1" type="text" placeholder="Firstname" />
                        </div>
                        <div>
                            <label for="lastname1" class="sr-only">Lastname</label>
                            <InputText id="lastname1" type="text" placeholder="Lastname" />
                        </div>
                        <Button label="Submit" :fluid="false" />
                    </div>
                </div>

                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Help Text</h3>
                    <div class="flex flex-col gap-2">
                        <label for="username">Username</label>
                        <InputText id="username" type="text" />
                        <small class="text-slate-500">Enter your username to reset your password.</small>
                    </div>
                </div>
            </div>

            <div class="col-span-12">
                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Advanced</h3>
                    <div class="flex flex-col gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="firstname2">Firstname</label>
                                <InputText id="firstname2" type="text" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="lastname2">Lastname</label>
                                <InputText id="lastname2" type="text" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="address">Address</label>
                            <Textarea id="address" rows="4" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="state">State</label>
                                <Select id="state" v-model="dropdownItem" :options="dropdownItems" optionLabel="name" placeholder="Select One" class="w-full" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="zip">Zip</label>
                                <InputText id="zip" type="text" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Fluid>
</template>
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/Pages/Uikit/FormLayout.vue
git commit -m "feat(ui): rewrite FormLayout page in Avalon style"
```

---

## Task 8: 统一剩余 UI Kit 页面卡片风格

**Files:**
- Modify: `resources/js/Pages/Uikit/*.vue`（ButtonDoc, InputDoc, TableDoc 等）

- [ ] **Step 1: 批量替换 `.card` 容器**

对每个 UI Kit 页面，将示例容器从：

```html
<div class="card">
```

保持为 `.card`（新样式会自动生效），但调整标题为：

```html
<h3 class="font-semibold text-lg mb-4">Section Title</h3>
```

确保页面根结构使用 `Fluid` 或 `<div class="flex flex-col gap-6">` 来匹配 Avalon 的垂直间距。

- [ ] **Step 2: Commit**

```bash
git add resources/js/Pages/Uikit
git commit -m "style(ui): apply Avalon card style to remaining UI kit pages"
```

---

## Task 9: 验证构建

**Files:**
- All modified files

- [ ] **Step 1: 安装依赖**

```bash
npm install
```

- [ ] **Step 2: 运行 Vite 构建**

```bash
npm run build
```

Expected: build completes without errors.

- [ ] **Step 3: 运行 Laravel Pint（可选）**

```bash
./vendor/bin/pint
```

- [ ] **Step 4: Commit any fixes**

```bash
git add .
git commit -m "fix: resolve build issues after Avalon migration"
```

---

## Self-Review

**Spec coverage:**
- ✅ 主色切换为 Avalon 蓝色（Task 1）
- ✅ 左侧分组图标边栏（Task 2 + Task 3）
- ✅ 顶部通栏（Task 3）
- ✅ 浅蓝灰背景 + 圆角白卡（Task 2）
- ✅ 登录页 Avalon 化（Task 5）
- ✅ Dashboard 与 widgets 更新（Task 6）
- ✅ FormLayout 重写（Task 7）
- ✅ 剩余 UI Kit 页面统一（Task 8）

**Placeholder scan:**
- 无 TBD/TODO
- 无 "implement later" / "fill in details"
- 所有代码步骤均包含完整代码

**Type consistency：**
- `layoutState.mobileMenuActive` 在 composable、AppLayout、AppTopbar 中一致
- 菜单数据结构在 AppMenu 和 AppMenuItem 中一致 `{ label, icon?, to? }`
- 主题 preset 名称 `AvalonPreset` 在 `app.js` 和导出文件中一致

---

## 执行方式

**Plan complete and saved to `docs/superpowers/plans/2026-06-12-avalon-ui-migration.md`. Two execution options:**

**1. Subagent-Driven (recommended)** - I dispatch a fresh subagent per task, review between tasks, fast iteration

**2. Inline Execution** - Execute tasks in this session using executing-plans, batch execution with checkpoints

**Which approach?**
