<script setup>
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    user: Object,
});

const { t } = useI18n();
const toast = useToast();

const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submitProfile = () => {
    profileForm.put('/profile', {
        preserveScroll: true,
        onSuccess: () => toast.add({ severity: 'success', summary: t('profile.updated'), life: 3000 }),
    });
};

const submitPassword = () => {
    passwordForm.put('/profile/password', {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            toast.add({ severity: 'success', summary: t('profile.passwordUpdated'), life: 3000 });
        },
    });
};
</script>

<template>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-6">
            <div class="card !mb-0">
                <div class="card-header">
                    <h3>{{ t('profile.info') }}</h3>
                </div>
                <div class="card-body">
                    <form @submit.prevent="submitProfile" class="flex flex-col gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="name" class="text-sm font-medium">{{ t('profile.name') }}</label>
                            <InputText id="name" v-model="profileForm.name" :invalid="!!profileForm.errors.name" />
                            <small v-if="profileForm.errors.name" class="text-red-500">{{ profileForm.errors.name }}</small>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="email" class="text-sm font-medium">{{ t('profile.email') }}</label>
                            <InputText id="email" v-model="profileForm.email" type="email" :invalid="!!profileForm.errors.email" />
                            <small v-if="profileForm.errors.email" class="text-red-500">{{ profileForm.errors.email }}</small>
                        </div>
                        <div>
                            <Button type="submit" :label="t('common.save')" :loading="profileForm.processing" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-6">
            <div class="card !mb-0">
                <div class="card-header">
                    <h3>{{ t('profile.changePassword') }}</h3>
                </div>
                <div class="card-body">
                    <form @submit.prevent="submitPassword" class="flex flex-col gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="current_password" class="text-sm font-medium">{{ t('profile.currentPassword') }}</label>
                            <Password id="current_password" v-model="passwordForm.current_password" :feedback="false" toggleMask :invalid="!!passwordForm.errors.current_password" class="w-full" inputClass="w-full" />
                            <small v-if="passwordForm.errors.current_password" class="text-red-500">{{ passwordForm.errors.current_password }}</small>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="password" class="text-sm font-medium">{{ t('profile.newPassword') }}</label>
                            <Password id="password" v-model="passwordForm.password" :feedback="false" toggleMask :invalid="!!passwordForm.errors.password" class="w-full" inputClass="w-full" />
                            <small v-if="passwordForm.errors.password" class="text-red-500">{{ passwordForm.errors.password }}</small>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="password_confirmation" class="text-sm font-medium">{{ t('profile.confirmPassword') }}</label>
                            <Password id="password_confirmation" v-model="passwordForm.password_confirmation" :feedback="false" toggleMask :invalid="!!passwordForm.errors.password_confirmation" class="w-full" inputClass="w-full" />
                            <small v-if="passwordForm.errors.password_confirmation" class="text-red-500">{{ passwordForm.errors.password_confirmation }}</small>
                        </div>
                        <div>
                            <Button type="submit" :label="t('common.save')" :loading="passwordForm.processing" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
