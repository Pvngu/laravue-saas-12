import './common/plugins';
import '../less/custom.less';
import 'ant-design-vue/dist/reset.css';

import { createApp } from 'vue';
import Antd from 'ant-design-vue';
import { PerfectScrollbarPlugin } from 'vue3-perfect-scrollbar';
import App from './main/views/App.vue';
import routes from './main/router'
import store from './main/store';
import { setupI18n, loadLocaleMessages } from './common/i18n';

import 'vue3-perfect-scrollbar/style.css';
import print from 'vue3-print-nb';

import AdminPageFilter from "./common/layouts/AdminPageFilters.vue";
import AdminPageTableContent from "./common/layouts/AdminPageTableContent.vue";

async function bootstrap() {
    if (store.getters["auth/isLoggedIn"]) {
        store.dispatch("auth/updateUser");
    }

    store.dispatch("auth/updateGlobalSetting");
    store.dispatch("auth/updateApp");
    store.dispatch("auth/updateAllLangs");
    store.commit("auth/updateActiveModules", window.config.modules);
    store.dispatch("auth/updateVisibleSubscriptionModules");

    const app = createApp(App);

    const i18n = setupI18n({ legacy: false, globalInjection: true, locale: store.state.auth.lang, warnHtmlMessage: false });
    await loadLocaleMessages(i18n, store.state.auth.lang);

    // app.config.devtools = true;
    // app.config.debug = true;

    app.use(i18n);
    app.use(Antd);
    app.use(PerfectScrollbarPlugin)
    app.use(store);
    app.use(print);

    // Global Components
    app.component('admin-page-filters', AdminPageFilter);
    app.component('admin-page-table-content', AdminPageTableContent);

    window.i18n = i18n;

    routes.isReady().then(() => {

        app.mount("#app");
    })
}

bootstrap();






