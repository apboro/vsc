require('./bootstrap');

import {createApp} from 'vue';
import {createRouter, createWebHistory} from 'vue-router';
import {createStore} from 'vuex';

import App from '@/Apps/AdminApp.vue';
import menu from '@/Definitions/Admin/Menu';
import routes from '@/Definitions/Admin/Routes';
import DictionaryStore from "@/Stores/dictionary-store";
import PermissionsStore from "@/Stores/permissions-store";

import Toast from "@/Plugins/Toast/toaster";
import Dialog from "@/Plugins/Dialog/dialog";
import Highlight from "@/Plugins/Highlight/highlight";

// add router
const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

// add store
const adminStore = createStore({
    modules: {
        dictionary: DictionaryStore,
        permissions: PermissionsStore,
    }
});

let user = typeof window.user === "undefined" ? null : JSON.parse(window.user);

router.beforeEach((to, from, next) => {
    let permission = to.meta['permission'];
    let role = to.meta['role'];
    let passed = true;
    if (typeof permission !== "undefined" && permission !== null && permission !== '') {
        if (typeof permission === "string") permission = [permission];
        passed = Object.keys(permission).some(key => adminStore.getters['permissions/can'](permission[key]));
    }
    if (passed && typeof role !== "undefined" && role !== null && role !== '') {
        if (typeof role === "string") role = [role];
        passed = Object.keys(role).some(key => adminStore.getters['permissions/hasRole'](role[key]));
    }
    if (!passed) {
        next({name: '403'});
    } else {
        next();
    }
});

const app = createApp(App, {menu: menu, user: user});

app.use(router);
app.use(adminStore);
app.use(Toast);
app.use(Dialog);
app.use(Highlight);

app.mount('#app');
