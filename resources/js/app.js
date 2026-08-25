import './bootstrap';
import '../css/app.css';
import '../css/avalon/layout/layout.scss';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import PrimeVue from 'primevue/config';
import { AvalonPreset } from './theme/avalon-preset';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import Toast from 'primevue/toast';
import ConfirmDialog from 'primevue/confirmdialog';
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
                    preset: AvalonPreset
                }
            })
            .use(ToastService)
            .use(ConfirmationService)
            .component('Toast', Toast)
            .component('ConfirmDialog', ConfirmDialog)
            .directive('styleclass', StyleClass);

        return app.mount(el);
    },
});
