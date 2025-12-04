import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';

// Importar componentes Vue
import DashboardVue from './components/DashboardVue.vue';
import ArticulosManager from './components/ArticulosManager.vue';
import RevistasManager from './components/RevistasManager.vue';
import ColumnistasManager from './components/ColumnistasManager.vue';

window.Alpine = Alpine;
Alpine.start();

// Inicializar Vue 3 - Dashboard
const appDashboard = document.getElementById('app-dashboard');
if (appDashboard) {
    createApp(DashboardVue).mount('#app-dashboard');
}

// Inicializar Vue 3 - Artículos
const appArticulos = document.getElementById('app');
if (appArticulos) {
    createApp(ArticulosManager).mount('#app');
}

// Inicializar Vue 3 - Revistas
const appRevistas = document.getElementById('app-revistas');
if (appRevistas) {
    createApp(RevistasManager).mount('#app-revistas');
}

// Inicializar Vue 3 - Columnistas
const appColumnistas = document.getElementById('app-columnistas');
if (appColumnistas) {
    createApp(ColumnistasManager).mount('#app-columnistas');
}
