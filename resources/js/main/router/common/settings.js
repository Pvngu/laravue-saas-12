import Translation from '../../views/settings/translations/index.vue';
import StorageEdit from '../../views/settings/storage/Edit.vue';
import EmailEdit from '../../views/settings/email/Edit.vue';
import DatabaseBackup from '../../views/settings/database-backup/index.vue';

// Defining route prefix and permission
// According to app_type
const routePrefix = 'superadmin';

export default [
    {
        path: 'translations',
        component: Translation,
        name: `${routePrefix}.settings.translations.index`,
        meta: {
            requireAuth: true,
            menuParent: "settings",
            menuKey: route => "translations",
            permission: "superadmin"
        }
    },
    {
        path: 'storage',
        component: StorageEdit,
        name: `${routePrefix}.settings.storage.index`,
        meta: {
            requireAuth: true,
            menuParent: "settings",
            menuKey: route => "storage_settings",
            permission: "superadmin"
        }
    },
    {
        path: 'email',
        component: EmailEdit,
        name: `${routePrefix}.settings.email.index`,
        meta: {
            requireAuth: true,
            menuParent: "settings",
            menuKey: route => "email_settings",
            permission: "superadmin"
        }
    },
    {
        path: 'database-backup',
        component: DatabaseBackup,
        name: 'admin.settings.database_backup.index',
        meta: {
            requireAuth: true,
            menuParent: "settings",
            menuKey: route => "database_backup",
            permission: "superadmin"
        }
    },
];
