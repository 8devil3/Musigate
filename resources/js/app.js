import './bootstrap';
import '../css/app.css';
import '../css/animations.css';
import dayjs from 'dayjs';
import 'dayjs/locale/it';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

dayjs.locale('it');

const appName = import.meta.env.VITE_APP_NAME || 'Musigate';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const myApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        myApp.config.globalProperties.dayjs = dayjs;
        myApp.mount(el);
    },
    progress: {
        color: '#ff6600',
    },
});
