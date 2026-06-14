import { createI18n } from 'vue-i18n';
import en from './en';
import zh from './zh';

const messages = {
    en,
    zh,
};

const savedLocale = localStorage.getItem('locale') || 'zh';

const i18n = createI18n({
    legacy: false,
    locale: savedLocale,
    fallbackLocale: 'en',
    messages,
});

export default i18n;
