# ICMS Agent Guide

## Project Overview

ICMS (Intelligent Cabin Management System / 智能方舱管理系统) is a Laravel 12 admin dashboard using Inertia.js, Vue 3, PrimeVue 4, and Tailwind CSS 4. The UI is built around a custom PrimeVue "Avalon" theme with a fixed sidebar, floating/collapsible topbar, and Chinese/English i18n support.

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP ^8.2 |
| Frontend | Vue 3, Inertia.js, PrimeVue 4.5, Tailwind CSS 4 |
| Build | Vite 7, `@tailwindcss/vite`, `unplugin-vue-components` |
| Styling | Tailwind utilities + custom Avalon SCSS |
| i18n | `vue-i18n` (frontend), Laravel translations minimal |
| Icons | PrimeIcons |
| Tests | PHPUnit 11 |

## Developer Setup

```bash
# First-time setup
composer setup

# Dev mode (Laravel + Vite + queue + pail)
composer dev

# Manual alternative
php artisan serve
npm run dev

# Production build
npm run build

# Run tests
composer test
```

## Project Structure

```
app/
  Http/Controllers/Auth/LoginController.php   # Auth: show, store, destroy
  Http/Middleware/HandleInertiaRequests.php   # Inertia shared props
  Http/Middleware/SetLocale.php               # Locale from cookie
  Models/User.php
bootstrap/app.php
config/
database/
  factories/
  migrations/                                 # Default users table
  seeders/
docs/superpowers/                             # AI plans/specs (not source of truth)
lang/{en,zh}/app.php
public/build/                                 # Compiled Vite assets
public/demo/                                  # PrimeVue demo assets
resources/
  css/avalon/layout/                          # Avalon theme SCSS
  css/app.css                                 # Tailwind entry
  js/
    app.js                                    # Entry: Inertia + PrimeVue + i18n
    bootstrap.js
    theme/avalon-preset.js                    # PrimeVue theme preset
    locales/{en,zh,index}.js                  # vue-i18n messages
    Layouts/                                  # AppLayout, AuthLayout, AppTopbar, AppSidebar, AppMenu, composables
    components/                               # Reusable components (AppLogo, etc.)
    Pages/                                    # Inertia pages
    service/                                  # Demo data services
routes/web.php
tests/
```

## Architecture Conventions

- **Composition API**: All Vue files use `<script setup>`.
- **Default layout**: `app.js` auto-applies `AppLayout` unless the page overrides it via `defineOptions({ layout: AuthLayout })`.
- **Auto-imports**: PrimeVue components are auto-resolved via `unplugin-vue-components` with `PrimeVueResolver`. Some components are still explicitly imported.
- **Inertia routing**: Use `Link` from `@inertiajs/vue3` for internal navigation. Do NOT use Vue Router (`as="router-link"` does not work here).
- **Aliases**:
  - `@` -> `resources/js`
  - `@/layout` -> `resources/js/Layouts`
  - Component directory is `@/components` (lowercase).
- **i18n**: Use `useI18n()` in Vue. Locale is persisted in `localStorage` and synced to backend via `POST /locale`.
- **Layout state**: Use `useLayout()` from `resources/js/Layouts/composables/layout.js` for menu/dark-mode state.
- **SCSS imports**: Avalon layout SCSS is imported in `app.js`. `app.css` is the Tailwind entry.

## Theme System

- PrimeVue preset: `resources/js/theme/avalon-preset.js` based on `@primeuix/themes/aura`.
- Primary color: blue scale.
- Surface color: slate scale.
- Dark mode: toggled by adding/removing `.app-dark` on `<html>`.
- Custom layout SCSS:
  - `resources/css/avalon/layout/_variables.scss`
  - `resources/css/avalon/layout/_topbar.scss`
  - `resources/css/avalon/layout/_sidebar.scss`
  - `resources/css/avalon/layout/_main.scss`
  - `resources/css/avalon/layout/_responsive.scss`

## Auth & Routing

| Route | Middleware | Purpose |
|-------|------------|---------|
| `GET/POST /auth/login` | guest | Login show/submit |
| `POST /logout` | auth | Logout |
| `GET /` | auth | Dashboard |
| `POST /locale` | - | Switch locale |
| `GET /auth/error` | - | Error page |
| `GET /auth/access` | - | Access denied |

- Sessions use database driver.
- Inertia shares `auth.user` globally.

## Verification Pipeline

Run these before considering work complete:

```bash
npm run build          # Vite production build
composer test          # PHPUnit
php artisan route:list # Verify routes
```

## Known Issues & Gotchas

- **UI Kit routes missing**: `AppMenu.vue` links to `/uikit/*` but no routes exist in `routes/web.php`.
- **Broken router-link usage**: `Auth/Error.vue` and `Auth/Access.vue` use `as="router-link"` with `to="/"`. Replace with Inertia `Link`.
- **`AppConfigurator.vue` references missing `changeMenuMode`**: `useLayout()` does not export this.
- **Duplicate CSS in `resources/css/app.css`**: `@source`, `@theme`, and `@import` blocks are duplicated, plus a duplicate import of Avalon SCSS.
- **Feature test fails**: `tests/Feature/ExampleTest.php` expects `GET /` to return 200, but `/` requires auth (returns 302).
- **SSR enabled but no bundle**: `config/inertia.php` has SSR enabled, but `bootstrap/ssr/ssr.mjs` does not exist.
- **Orphaned pages**: `Welcome.vue` has no route.
- **README is default Laravel skeleton**: not project-specific.

## Security Notes

- `.env` contains real database credentials for a remote host. Do not commit `.env`. Rotate credentials if they are live.
- No registration, password reset, or email verification is implemented.

## Environment

Current `.env` uses MySQL on `39.102.54.120`. `.env.example` uses SQLite. The app defaults to Chinese locale (`zh`) with English (`en`) also supported.
