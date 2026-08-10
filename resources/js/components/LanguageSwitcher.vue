<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { router } from '@inertiajs/vue3';
import Select from 'primevue/select';

const { t, locale } = useI18n();

const options = [
    { label: '中文', value: 'zh' },
    { label: 'English', value: 'en' },
];

const selectedLanguage = computed({
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
        v-model="selectedLanguage"
        :options="options"
        optionLabel="label"
        :placeholder="t('language.switchLanguage')"
        :aria-label="t('language.switchLanguage')"
        class="w-32"
        size="small"
    />
</template>
