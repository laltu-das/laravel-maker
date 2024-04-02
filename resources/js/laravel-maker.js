/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import './bootstrap';
import '../css/laravel-maker.css';

import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/vue3'
import AppLayout from "@/AppLayout.vue";
import {ZiggyVue} from '../../vendor/tightenco/ziggy';

createInertiaApp({
    title: (title) => `${title} - ${process.env.MIX_APP_NAME} Laravel Maker`,
    resolve: (name) => {
        const page = require(`./Pages/${name}`).default;
        if (!page.layout) {
            page.layout = AppLayout;
        }
        return page;
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    },
}).then(r => console.log(r))
