import { createRouter, createWebHistory } from "vue-router";
import store from "../store";

import AuthRoutes from "./auth";
import DashboardRoutes from "./dashboard";
import UserRoutes from "./users";
import MessagingRoutes from "./messaging";
import SettingRoutes from "./settings";
import superAdminRoutes from "../../superadmin/router/index";
import subscriptionRoutes from "../../superadmin/router/admin/index";
import { checkUserPermission } from "../../common/scripts/functions";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "",
            redirect: "/admin/login",
        },
        ...AuthRoutes,
        ...superAdminRoutes,
        ...subscriptionRoutes,
        ...DashboardRoutes,
        ...UserRoutes,
        ...SettingRoutes,
        ...MessagingRoutes
    ],
    scrollBehavior: () => ({ left: 0, top: 0 }),
});

router.beforeEach((to, from, next) => {
    const { user } = store.state.auth;
    const isLoggedIn = store.getters["auth/isLoggedIn"];
    const isSuperAdmin = user?.is_superadmin;
    store.commit("auth/updateAppChecking", false);

    const redirectToLogin = () => {
        store.dispatch("auth/logout");
        next({ name: "admin.login" });
    };

    if (to.meta.requireAuth && !isLoggedIn) {
        return redirectToLogin();
    }

    if (to.name.startsWith("superadmin")) {
        if (to.meta.requireAuth && isLoggedIn && !isSuperAdmin) {
            return redirectToLogin();
        }
        if (to.meta.requireUnauth && isLoggedIn) {
            return next({ name: "superadmin.dashboard.index" });
        }
    } else if (to.name.startsWith("admin") && !to.name.startsWith("admin.login")) {
        if (isSuperAdmin) {
            return next({ name: "superadmin.dashboard.index" });
        }
        if (to.meta.requireUnauth && isLoggedIn) {
            return next({ name: "admin.dashboard.index" });
        }
        if (to.name === 'saas.settings.modules.index') {
            return next();
        }
        const { permission } = to.meta;
        if (!permission || checkUserPermission(permission, user)) {
            return next();
        }
        return next({ name: "admin.dashboard.index" });
    }
    next();
});

export default router;