# Login Page Header Logo and Language Switcher

## Goal

Add a branded header logo and a language switcher to the `/auth/login` page of the ICMS Laravel + Inertia + Vue application.

## Context

- Stack: Laravel 11, Inertia.js with Vue 3, PrimeVue 4, Tailwind CSS v4, vue-i18n.
- The login page is rendered by `App\Http\Controllers\Auth\LoginController::show()` as `Auth/Login`.
- The current `AuthLayout.vue` only centers the page content on a blue background; it has no top bar.
- `Login.vue` already displays `AppLogo` inside the login card above the "Welcome" text.
- The backend already supports locale switching via `POST /locale` and a `SetLocale` middleware that reads a `locale` cookie.
- The frontend already initializes vue-i18n from `localStorage.getItem('locale')` and ships `zh`/`en` message files.

## Requirements

1. **Header logo**: Show the existing `AppLogo` component in a top bar on `/auth/login`.
2. **Language switcher**: Allow the user to switch between Chinese (`zh`) and English (`en`) inside the login card.
3. **Consistency**: Match the existing Avalon/PrimeVue theme (colors, spacing, dark-mode classes).
4. **No duplication**: Build reusable components where appropriate.
5. **Keep existing behavior**: The logo inside the login card must remain.

## Design

### Components

- **`resources/js/components/LanguageSwitcher.vue`**
  - A PrimeVue `Select` dropdown with options for `zh` and `en`.
  - Displays the native label for each language (`中文`, `English`).
  - On change:
    1. Update the vue-i18n locale.
    2. Persist the choice to `localStorage`.
    3. POST to the existing `/locale` route (`preserveState: false`) so the backend `locale` cookie stays in sync and Inertia reloads the current page content.

- **`resources/js/Layouts/AuthTopbar.vue`**
  - A minimal top bar for auth pages.
  - Left side: `AppLogo`.
  - No other controls (language switcher lives in the login card).
  - Styled with Tailwind to sit at the top of the viewport over the auth background.

### Layout and Page Changes

- **`resources/js/Layouts/AuthLayout.vue`**
  - Render `AuthTopbar` above the centered `<slot>` content.
  - Ensure the main content is still vertically and horizontally centered.

- **`resources/js/Pages/Auth/Login.vue`**
  - Import and place `LanguageSwitcher` inside the login card, below the sign-in button.
  - Keep the existing `AppLogo` at the top of the card.

### Styling

- Use the same background (`bg-[#f3f8ff]`) and spacing scale already used in `AuthLayout.vue`.
- Use `text-surface-900 dark:text-surface-0` and other existing Tailwind utility classes from the project.
- Ensure the top bar does not overlap the login card on small screens by adding appropriate top padding to the centered content area.

## Out of Scope

- Adding the language switcher to `AppTopbar` or other authenticated pages.
- Changing backend locale logic or translation files.
- Password reset or register flows.

## Success Criteria

- `/auth/login` displays a top bar with the logo.
- The login card still shows the logo and the form.
- A language switcher appears inside the login card and successfully toggles the UI between Chinese and English.
- The selected language persists after a page refresh.
- No visual regressions on desktop or mobile viewports.
