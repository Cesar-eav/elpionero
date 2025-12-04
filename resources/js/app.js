import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';

// Importar componentes Vue
import ArticulosManager from './components/ArticulosManager.vue';
import ArticulosTable from './components/ArticulosTable.vue';
import ArticulosForm from './components/ArticulosForm.vue';
import ExampleComponent from './components/ExampleComponent.vue';

window.Alpine = Alpine;
Alpine.start();

// Inicializar Vue 3
// Crear la app de Vue si existe el elemento #app
const appElement = document.getElementById('app');
if (appElement) {
    const app = createApp(ArticulosManager);
    app.mount('#app');
}
