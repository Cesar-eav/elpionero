# Guía de Configuración Vue 3 + Laravel

## 🎉 Estructura Creada

### Archivos API (Laravel)
- **routes/api.php** - Rutas API para artículos
- **app/Http/Controllers/Api/ArticuloApiController.php** - Controlador API

### Componentes Vue 3
- **resources/js/components/ArticulosManager.vue** - Componente principal que coordina tabla y formulario
- **resources/js/components/ArticulosTable.vue** - Tabla de artículos con búsqueda y paginación
- **resources/js/components/ArticulosForm.vue** - Formulario modal para crear/editar artículos

### Vistas Blade
- **resources/views/articulos/vue-index.blade.php** - Vista de ejemplo que integra Vue

### Configuración
- **resources/js/app.js** - Actualizado para registrar componentes Vue
- **routes/web.php** - Agregada ruta `/articulos-vue`

## 🚀 Endpoints API Disponibles

```
GET    /api/articulos              - Listar artículos (con paginación y filtros)
GET    /api/articulos/{id}         - Obtener un artículo específico
POST   /api/articulos              - Crear artículo
PUT    /api/articulos/{id}         - Actualizar artículo
DELETE /api/articulos/{id}         - Eliminar artículo
GET    /api/revistas               - Listar revistas
GET    /api/columnistas            - Listar columnistas
```

### Parámetros de búsqueda (GET /api/articulos)
- `search` - Buscar en título, contenido o autor
- `revista_id` - Filtrar por revista
- `columnista_id` - Filtrar por columnista
- `per_page` - Artículos por página (default: 15)

## 💻 Cómo Usar

### Opción 1: Vista Completa con Vue
Acceder a: `http://tu-dominio.com/articulos-vue`

Esta ruta muestra una interfaz completamente construida con Vue que consume la API.

### Opción 2: Insertar Componentes en Vistas Blade Existentes

Puedes insertar componentes Vue en cualquier vista Blade:

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Página</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Contenido Blade normal -->
    <h1>Bienvenido {{ auth()->user()->name }}</h1>

    <!-- Componente Vue -->
    <div id="app">
        <articulos-manager></articulos-manager>
    </div>
</body>
</html>
```

### Opción 3: Usar Componentes Individuales

```blade
<div id="app">
    <!-- Solo la tabla -->
    <articulos-table></articulos-table>

    <!-- O solo el formulario -->
    <articulos-form :show="true"></articulos-form>
</div>
```

## 🛠️ Comandos de Desarrollo

```bash
# Modo desarrollo (con hot reload)
npm run dev

# Compilar para producción
npm run build
```

## 📝 Ejemplo de Uso de la API con Axios

```javascript
// Obtener artículos
axios.get('/api/articulos', {
    params: {
        search: 'término de búsqueda',
        revista_id: 1,
        page: 1
    }
})
.then(response => {
    console.log(response.data);
});

// Crear artículo
const formData = new FormData();
formData.append('titulo', 'Nuevo Artículo');
formData.append('contenido', 'Contenido del artículo');
formData.append('revista_id', 1);
formData.append('columnista_id', 1);

axios.post('/api/articulos', formData)
.then(response => {
    console.log(response.data.message);
});

// Eliminar artículo
axios.delete('/api/articulos/1')
.then(response => {
    console.log(response.data.message);
});
```

## 🎨 Características de los Componentes

### ArticulosTable
- ✅ Búsqueda en tiempo real
- ✅ Paginación
- ✅ Mostrar relaciones (columnista, revista)
- ✅ Botones de acción (editar, eliminar)
- ✅ Estados de carga

### ArticulosForm
- ✅ Modal para crear/editar
- ✅ Validación de formularios
- ✅ Carga de imágenes
- ✅ Selects dinámicos (revistas, columnistas)
- ✅ Manejo de errores

### ArticulosManager
- ✅ Coordina tabla y formulario
- ✅ Maneja eventos entre componentes
- ✅ Refresca datos después de crear/editar

## 🔐 CSRF Token

El token CSRF ya está configurado en `bootstrap.js`:

```javascript
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

Solo asegúrate de incluir en tus vistas Blade:

```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## 📦 Crear Nuevos Componentes Vue

1. Crear archivo en `resources/js/components/MiComponente.vue`
2. Registrarlo en `resources/js/app.js`:

```javascript
import MiComponente from './components/MiComponente.vue';

// Agregarlo a components:
createApp({
    components: {
        MiComponente,
        // ... otros componentes
    }
}).mount('#app');
```

3. Compilar: `npm run dev` o `npm run build`
4. Usar en Blade: `<mi-componente></mi-componente>`

## 🌐 Arquitectura Híbrida

Esta configuración te permite:
- Usar **Laravel/Blade** para rutas, layouts y SEO
- Usar **Vue 3** para componentes interactivos
- **API REST** para comunicación
- **Axios** para peticiones HTTP

No necesitas Vue Router porque Laravel maneja las rutas principales.

## ⚡ Próximos Pasos

1. Personaliza los estilos de los componentes según tu diseño
2. Agrega más filtros en la tabla si es necesario
3. Implementa autenticación en las rutas API si lo requieres
4. Crea más componentes para otras secciones (revistas, columnistas, etc.)

## 🐛 Solución de Problemas

**Error: Components not found**
- Asegúrate de haber ejecutado `npm run dev` o `npm run build`
- Verifica que `@vite(['resources/js/app.js'])` esté en tu vista Blade

**Error: API 404**
- Verifica que las rutas API estén en `routes/api.php`
- Las rutas API tienen prefijo `/api/` automáticamente

**Error: CSRF Token Mismatch**
- Incluye `<meta name="csrf-token" content="{{ csrf_token() }}">` en el head
- Axios automáticamente incluirá el token
