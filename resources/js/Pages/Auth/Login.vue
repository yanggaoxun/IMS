<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AppLogo from '@/components/AppLogo.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
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

            <div class="mt-6 flex justify-center items-center gap-2 text-slate-500">
                <i class="pi pi-globe"></i>
                <LanguageSwitcher />
            </div>
        </div>
    </div>
</template>
