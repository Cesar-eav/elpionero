import { createRouter, createWebHistory } from 'vue-router';
import DashboardVue from './components/DashboardVue.vue';
import ArticulosManager from './components/ArticulosManager.vue';
import EditorialesManager from './components/EditorialesManager.vue';
import NoticiasManager from './components/NoticiasManager.vue';
import EntrevistasManager from './components/EntrevistasManager.vue';
import CableATierraManager from './components/CableATierraManager.vue';
import RevistasManager from './components/RevistasManager.vue';
import ColumnistasManager from './components/ColumnistasManager.vue';

const routes = [
    {
        path: '/dashboard-vue',
        name: 'dashboard',
        component: DashboardVue,
        meta: {
            title: 'Dashboard'
        }
    },
    {
        path: '/dashboard-vue/articulos',
        name: 'articulos',
        component: ArticulosManager,
        meta: {
            title: 'Gestión de Artículos'
        }
    },
    {
        path: '/dashboard-vue/editoriales',
        name: 'editoriales',
        component: EditorialesManager,
        meta: {
            title: 'Gestión de Editoriales'
        }
    },
    {
        path: '/dashboard-vue/noticias',
        name: 'noticias',
        component: NoticiasManager,
        meta: {
            title: 'Gestión de Noticias'
        }
    },
    {
        path: '/dashboard-vue/entrevistas',
        name: 'entrevistas',
        component: EntrevistasManager,
        meta: {
            title: 'Gestión de Entrevistas'
        }
    },
    {
        path: '/dashboard-vue/revistas',
        name: 'revistas',
        component: RevistasManager,
        meta: {
            title: 'Gestión de Revistas'
        }
    },
    {
        path: '/dashboard-vue/columnistas',
        name: 'columnistas',
        component: ColumnistasManager,
        meta: {
            title: 'Gestión de Columnistas'
        }
    },

    {
        path: '/dashboard-vue/cable',
        name: 'cable',
        component: CableATierraManager,
        meta: {
            title: 'Cable a Tierra'
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
