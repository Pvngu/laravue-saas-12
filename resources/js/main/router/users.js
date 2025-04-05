import UserIndex from '../views/users/index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/users',
                component: UserIndex,
                name: 'admin.users.index',
                meta: {
                    requireAuth: true,
                    menuParent: "users",
                    menuKey: "users",
                    permission: "users_view"
                }
            },
        ]

    }
]
