# IMS — 管理系统起始模板

基于 Laravel 12 + Inertia.js + Vue 3 + PrimeVue 4（Avalon 主题）+ Tailwind CSS 4 的后台管理系统模板，作为后续项目的起始状态。

## 技术栈

| 层 | 技术 |
|----|------|
| 后端 | Laravel 12, PHP ^8.2 |
| 前端 | Vue 3 (Composition API), Inertia.js, PrimeVue 4, Tailwind CSS 4 |
| 构建 | Vite 7, unplugin-vue-components（PrimeVue 组件自动导入） |
| 国际化 | vue-i18n（中文/英文），Laravel 翻译 |
| 测试 | PHPUnit 11 |

## 快速开始

```bash
composer setup    # 首次：装依赖、生成 key、迁移、构建前端
composer dev      # 开发：server + queue + logs + vite 一条命令全起
composer test     # 测试
npm run build     # 生产构建
```

## 基于此模板开新项目

1. 复制仓库后改名（只需两处）：
   - `.env` 的 `APP_NAME`（控制浏览器标签页标题）
   - `resources/js/locales/{zh,en}.js` 的 `brand.name` / `brand.shortName`（控制侧边栏 Logo 和页脚）
2. 更新数据库配置并迁移：`php artisan migrate`

## 目录结构

```
app/Http/Controllers/        # LoginController, ProfileController
app/Http/Middleware/         # HandleInertiaRequests, SetLocale
resources/js/
  Pages/                     # Inertia 页面（Dashboard, Profile, Error, Auth/*, Uikit/*）
  Layouts/                   # AppLayout, AuthLayout, AppTopbar, AppSidebar, composables/layout.js
  components/                # 通用组件 + dashboard/ 演示 widget
  locales/{zh,en}.js         # 前端 i18n 文案
  theme/avalon-preset.js     # PrimeVue 主题预设
resources/css/avalon/layout/ # 布局 SCSS（浅色）
routes/web.php               # 全部路由
```

## 开发规范

- **新增页面**：`Pages/` 下建 `.vue` 文件（自动套用 AppLayout）→ `routes/web.php` 加路由 → 如需菜单，在 `Layouts/AppMenu.vue` 加项并在 `locales` 加 `nav.*` 文案；顶栏标题和面包屑会自动跟随路由
- **样式**：卡片用 `.card` / `.card-header` / `.card-body`；颜色用 PrimeVue 主题变量或 Tailwind 工具类；模板只支持浅色模式
- **文案**：一律走 `useI18n()`，不要硬编码
- **导航**：内部跳转用 `@inertiajs/vue3` 的 `Link`，不要引入 Vue Router

## 内置功能

- 登录 / 登出（仅认证，不含注册、忘记密码）
- 个人资料页（改姓名/邮箱/密码）：`/profile`
- 中英双语切换
- 主题换肤面板（主色 / surface / preset）
- Inertia 错误页（403/404/500/503）
- UI Kit 演示页 14 个（`/uikit/*`，作为组件用法参考）

详细约定见 `AGENTS.md`。
