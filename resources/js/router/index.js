import { createRouter, createWebHistory } from 'vue-router';
import DashboardVue from '../components/DashboardVue.vue';
import ArticulosManager from '../components/ArticulosManager.vue';
import EditorialesManager from '../components/EditorialesManager.vue';
import NoticiasManager from '../components/NoticiasManager.vue';
import EntrevistasManager from '../components/EntrevistasManager.vue';
import RevistasManager from '../components/RevistasManager.vue';
import ColumnistasManager from '../components/ColumnistasManager.vue';

const routes = [
    {
        path: '/dashboard-vue',
        name: 'dashboard',
        component: DashboardVue,
        meta: { title: 'Dashboard' }
    },
    {
        path: '/dashboard-vue/articulos',
        name: 'articulos',
        component: ArticulosManager,
        meta: { title: 'Artículos' }
    },
    {
        path: '/dashboard-vue/editoriales',
        name: 'editoriales',
        component: EditorialesManager,
        meta: { title: 'Editoriales' }
    },
    {
        path: '/dashboard-vue/noticias',
        name: 'noticias',
        component: NoticiasManager,
        meta: { title: 'Noticias' }
    },
    {
        path: '/dashboard-vue/entrevistas',
        name: 'entrevistas',
        component: EntrevistasManager,
        meta: { title: 'Entrevistas' }
    },
    {
        path: '/dashboard-vue/revistas',
        name: 'revistas',
        component: RevistasManager,
        meta: { title: 'Revistas' }
    },
    {
        path: '/dashboard-vue/columnistas',
        name: 'columnistas',
        component: ColumnistasManager,
        meta: { title: 'Columnistas' }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
