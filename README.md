# IMS — 管理系统起始模板

基于 Laravel 12 + Inertia.js + Vue 3 + PrimeVue 4（Avalon 主题）+ Tailwind CSS 4 的后台管理系统模板，作为后续项目的起始状态。

## 技术栈

| 层 | 技术 |
|----|------|
| 后端 | Laravel 12, PHP ^8.2, Laravel Octane (FrankenPHP) |
| 前端 | Vue 3 (Composition API), Inertia.js, PrimeVue 4, Tailwind CSS 4 |
| RBAC | spatie/laravel-permission（角色/权限/菜单过滤） |
| 构建 | Vite 7, unplugin-vue-components（PrimeVue 组件自动导入） |
| 国际化 | vue-i18n（中文/英文），Laravel 翻译 |
| 测试 | PHPUnit 11 |
| CI/CD | Woodpecker CI → Harbor（`.woodpecker.yml`） |
| 部署 | Docker（多阶段 Dockerfile，FrankenPHP 运行时） |

## 快速开始

```bash
composer setup    # 首次：装依赖、生成 key、迁移、构建前端
composer dev      # 开发：server + queue + logs + vite 一条命令全起
composer test     # 测试
npm run build     # 生产构建
```

## Docker 方式启动（类生产）

```bash
cp .env.example .env   # 按需修改数据库等配置
docker compose up -d --build
# 应用监听 http://localhost:8000（FrankenPHP + Octane）
```

## CI/CD

推送到 `develop` 或 `main` 触发 Woodpecker 流水线（`.woodpecker.yml`）：

1. `composer install` + `php artisan test`
2. 构建 Docker 镜像并推送到 Harbor：`8.148.225.109:8080/ci/ims:<短sha>` 和 `:latest`

需要在 Woodpecker 仓库设置里配置 secrets：`harbor_username` / `harbor_password`（Harbor 机器人账号）。流水线不自动部署，部署时到目标机器 `docker pull` 后用 `docker compose up -d` 拉起。

## 基于此模板开新项目

1. 复制仓库后改名（只需两处）：
   - `.env` 的 `APP_NAME`（控制浏览器标签页标题）
   - `resources/js/locales/{zh,en}.js` 的 `brand.name` / `brand.shortName`（控制侧边栏 Logo 和页脚）
2. 更新数据库配置并迁移：`php artisan migrate`

## 目录结构

```
app/Http/Controllers/        # LoginController, ProfileController, UserController, RoleController
app/Http/Middleware/         # HandleInertiaRequests, SetLocale
resources/js/
  Pages/                     # Inertia 页面（Dashboard, Profile, Error, Auth/*, Users/, Roles/, Uikit/*）
  Layouts/                   # AppLayout, AuthLayout, AppTopbar, AppSidebar, composables/layout.js
  components/                # 通用组件 + dashboard/ 演示 widget
  locales/{zh,en}.js         # 前端 i18n 文案
  theme/avalon-preset.js     # PrimeVue 主题预设
resources/css/avalon/layout/ # 布局 SCSS（浅色）
routes/web.php               # 全部路由
Dockerfile                   # 多阶段：assets → vendor → FrankenPHP 运行时
.woodpecker.yml              # CI 流水线
docker-compose.yml           # 类生产启动
```

## 内置功能

- 登录 / 登出（仅认证，不含注册、忘记密码）
- 个人资料页（改姓名/邮箱/密码）：`/profile`
- 用户管理 `/users`、角色权限管理 `/roles`（RBAC，菜单按权限过滤）
- 中英双语切换
- 主题换肤面板（主色 / surface / preset）
- Inertia 错误页（403/404/500/503）
- UI Kit 演示页 14 个（`/uikit/*`，作为组件用法参考）
