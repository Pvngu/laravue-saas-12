import Login from '../views/auth/Login.vue';

export default [
    {
        path: '/admin/login',
        component: Login,
        name: 'admin.login',
        meta: {
            requireUnauth: true,
            menuKey: route => "login",
        }
    },
]
