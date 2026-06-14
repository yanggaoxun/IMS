> I'm using the writing-plans skill to create the implementation plan.

# Login Page Header Logo and Language Switcher Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use `superpowers:subagent-driven-development` (recommended) or `superpowers:executing-plans` to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a branded top bar with the logo and a language switcher inside the login card on `/auth/login`.

**Architecture:** Introduce two focused Vue components (`AuthTopbar`, `LanguageSwitcher`) and wire them into the existing `AuthLayout` and `Auth/Login` pages. The language switcher reuses the existing `/locale` backend route and vue-i18n setup.

**Tech Stack:** Laravel 11, Inertia.js + Vue 3, PrimeVue 4, Tailwind CSS v4, vue-i18n.

---

## Files

- **Create:** `resources/js/components/LanguageSwitcher.vue`
- **Create:** `resources/js/Layouts/AuthTopbar.vue`
- **Modify:** `resources/js/Layouts/AuthLayout.vue`
- **Modify:** `resources/js/Pages/Auth/Login.vue`

---

### Task 1: Create `LanguageSwitcher.vue`

**Files:**
- Create: `resources/js/components/LanguageSwitcher.vue`

**Prerequisites:** None

- [ ] **Step 1: Verify PrimeVue `Select` is available**

  Check that `primevue/select` can be imported. The project already imports PrimeVue and registers Toast/StyleClass globally; component auto-import is handled by `unplugin-vue-components`, so using `<Select>` in a template should work without explicit import. We will still import explicitly for clarity.

- [ ] **Step 2: Write the component**

  ```vue
  <script setup>
  import { computed } from 'vue';
  import { useI18n } from 'vue-i18n';
  import { router } from '@inertiajs/vue3';
  import Select from 'primevue/select';

  const { locale } = useI18n();

  const options = [
      { label: '中文', value: 'zh' },
      { label: 'English', value: 'en' },
  ];

  const selected = computed({
      get: () => options.find((option) => option.value === locale.value) || options[0],
      set: (option) => {
          if (!option || locale.value === option.value) {
              return;
          }

          const newLocale = option.value;
          locale.value = newLocale;
          localStorage.setItem('locale', newLocale);

          router.post(
              '/locale',
              { locale: newLocale },
              {
                  preserveState: false,
                  preserveScroll: false,
              },
          );
      },
  });
  </script>

  <template>
      <Select
          v-model="selected"
          :options="options"
          optionLabel="label"
          :placeholder="t('language.switchLanguage')"
          :aria-label="t('language.switchLanguage')"
          class="w-32"
      />
  </template>
  ```

- [ ] **Step 3: Commit**

  ```bash
  git add resources/js/components/LanguageSwitcher.vue
  git commit -m "feat(auth): add reusable LanguageSwitcher component"
  ```

---

### Task 2: Create `AuthTopbar.vue`

**Files:**
- Create: `resources/js/Layouts/AuthTopbar.vue`

**Prerequisites:** Task 1 (for import path reference, though `AuthTopbar` does not use `LanguageSwitcher`)

- [ ] **Step 1: Write the component**

  ```vue
  <script setup>
  import AppLogo from '@/Components/AppLogo.vue';
  </script>

  <template>
      <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-6 py-4 bg-white/80 backdrop-blur dark:bg-surface-900/80 border-b border-slate-200 dark:border-surface-700">
          <AppLogo />
      </header>
  </template>
  ```

- [ ] **Step 2: Commit**

  ```bash
  git add resources/js/Layouts/AuthTopbar.vue
  git commit -m "feat(auth): add AuthTopbar with logo"
  ```

---

### Task 3: Update `AuthLayout.vue`

**Files:**
- Modify: `resources/js/Layouts/AuthLayout.vue`

**Prerequisites:** Task 2

- [ ] **Step 1: Update layout to render `AuthTopbar` and adjust content spacing**

  ```vue
  <script setup>
  import AuthTopbar from './AuthTopbar.vue';
  </script>

  <template>
      <div class="min-h-screen flex flex-col bg-[#f3f8ff]">
          <AuthTopbar />
          <main class="flex-1 flex items-center justify-center px-4 pt-24 pb-8">
              <slot />
          </main>
      </div>
  </template>
  ```

- [ ] **Step 2: Verify no visual overlap**

  Start the dev server if not already running:

  ```bash
  npm run dev
  ```

  Open `http://localhost:8000/auth/login` and confirm:
  - The top bar is visible at the top.
  - The login card is centered and not hidden behind the top bar.

- [ ] **Step 3: Commit**

  ```bash
  git add resources/js/Layouts/AuthLayout.vue
  git commit -m "feat(auth): render AuthTopbar in AuthLayout"
  ```

---

### Task 4: Update `Login.vue`

**Files:**
- Modify: `resources/js/Pages/Auth/Login.vue`

**Prerequisites:** Task 1

- [ ] **Step 1: Import `LanguageSwitcher`**

  Add to the `<script setup>` block:

  ```javascript
  import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
  ```

- [ ] **Step 2: Place the switcher inside the card**

  Add the following markup just before the closing `</form>` tag (below the submit button):

  ```vue
  <div class="mt-6 flex justify-center">
      <LanguageSwitcher />
  </div>
  ```

- [ ] **Step 3: Verify the full file looks correct**

  Expected final `Login.vue` `<script setup>` imports:

  ```javascript
  import AuthLayout from '@/Layouts/AuthLayout.vue';
  import AppLogo from '@/Components/AppLogo.vue';
  import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
  import { useForm } from '@inertiajs/vue3';
  import { useI18n } from 'vue-i18n';
  ```

  Expected final form closing area:

  ```vue
  <Button type="submit" :label="t('login.signIn')" class="w-full" :loading="form.processing" />

  <div class="mt-6 flex justify-center">
      <LanguageSwitcher />
  </div>
  ```

- [ ] **Step 4: Test language switching**

  With the dev server running, open `http://localhost:8000/auth/login`:
  - Confirm the dropdown shows the current language.
  - Switch to English and verify the page reloads with English labels (`Welcome to ICMS!`, `Email`, `Password`, `Sign In`).
  - Switch to Chinese and verify the page reloads with Chinese labels (`欢迎来到 ICMS!`, `邮箱`, `密码`, `登录`).
  - Refresh the page and confirm the selected language persists.

- [ ] **Step 5: Commit**

  ```bash
  git add resources/js/Pages/Auth/Login.vue
  git commit -m "feat(auth): add language switcher to login card"
  ```

---

### Task 5: Final Verification

**Files:** None

- [ ] **Step 1: Run the build**

  ```bash
  npm run build
  ```

  Expected: build completes without errors.

- [ ] **Step 2: Check responsive layout**

  Open `http://localhost:8000/auth/login` and resize the browser to a mobile width. Confirm:
  - The top bar logo remains visible.
  - The login card stays centered and readable.
  - The language switcher does not overflow the card.

- [ ] **Step 3: Review git diff**

  ```bash
  git diff --stat
  ```

  Expected changed files:
  - `resources/js/Layouts/AuthLayout.vue`
  - `resources/js/Layouts/AuthTopbar.vue` (new)
  - `resources/js/Pages/Auth/Login.vue`
  - `resources/js/components/LanguageSwitcher.vue` (new)

---

## Spec Coverage Check

| Spec Requirement | Implementing Task |
| --- | --- |
| Header logo in top bar | Task 2 + Task 3 |
| Language switcher inside login card | Task 1 + Task 4 |
| Keep existing logo inside login card | Task 4 (no removal) |
| Sync locale with backend `/locale` cookie | Task 1 (`router.post`) |
| Match existing theme | All tasks (Tailwind/PrimeVue classes) |
| Mobile layout | Task 5 |

## Placeholder Scan

- No `TBD`, `TODO`, or vague steps.
- Every code change is shown in full.
- Every test/verification step includes exact commands or observable criteria.
