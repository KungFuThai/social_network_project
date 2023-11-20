import { createWebHistory, createRouter } from 'vue-router';

import Home from '../pages/Home.vue';
import Register from '../pages/Register.vue';
import Login from '../pages/Login.vue';
import Chat from '../pages/ChatLayout.vue';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'chat',
        path: '/messages',
        component: Chat
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;