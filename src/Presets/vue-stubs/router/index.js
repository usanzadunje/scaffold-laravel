import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        name: 'welcome',
        component: () => import( '@/views/Welcome.vue'),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;