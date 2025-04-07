
import CommonSettings from "./settings";
import EmailEdit from '../../views/settings/mail-settings/Edit.vue';

const allRoutes = [
    {
        path: 'email',
        component: EmailEdit,
        name: 'admin.settings.email.index',
        meta: {
            requireAuth: true,
            menuParent: "settings",
            menuKey: route => "email_settings",
            permission: "email_edit"
        }
    },
];

export default allRoutes;
