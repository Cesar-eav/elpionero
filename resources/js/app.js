import './bootstrap';
import './dropdown';

import { createApp } from 'vue';
import router from './router';
import DashboardLayout from './components/DashboardLayout.vue';

// Inicializar Vue 3 - Dashboard con Router
const appDashboard = document.getElementById('app-dashboard');
if (appDashboard) {
    const userName = appDashboard.dataset.userName;
    const csrfToken = appDashboard.dataset.csrfToken;
    const logoutUrl = appDashboard.dataset.logoutUrl;

    createApp(DashboardLayout, {
        userName,
        csrfToken,
        logoutUrl
    })
        .use(router)
        .mount('#app-dashboard');
}
