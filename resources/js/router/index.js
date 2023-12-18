import { createWebHistory, createRouter } from 'vue-router';

import Home from '../pages/Home.vue';
import Register from '../pages/Register.vue';
import Login from '../pages/Login.vue';
import Forget from '../pages/Forget.vue';
import Chat from '../pages/ChatLayout.vue';
import HomeLayout from "../pages/HomeLayout.vue";
import Profile from "../pages/ProfilePage.vue";

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home,
        meta: {requiresAuth: true},
        children: [
            {
                path: '/',
                name: '',
                props: true,
                component: HomeLayout
            },
            {
                path: '/messages/:id?',
                name: 'messages',
                props: true,
                component: Chat
            },
            {
                path: '/profile/:id',
                name: 'profile',
                props: true,
                component: Profile
            },
        ]
    },
    {
        name: 'register',
        path: '/register',
        component: Register,
        meta: {guest: true}
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta: {guest: true}
    },
    {
        name: 'forget',
        path: '/forget',
        component: Forget,
        meta: {guest: true}
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;