import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';

window.Alpine = Alpine;
Alpine.start();

// Inicializar Vue 3
// Puedes importar tus componentes aquí
// import ExampleComponent from './components/ExampleComponent.vue';

// Crear la app de Vue si existe el elemento #app
const app = document.getElementById('app');
if (app) {
    createApp({
        // Aquí puedes registrar componentes globales
        // components: {
        //     ExampleComponent
        // }
    }).mount('#app');
}
