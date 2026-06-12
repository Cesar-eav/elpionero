# El Pionero de Valparaíso — Guía de Proyecto para Claude

Plataforma editorial local (noticias, columnas, revistas, entrevistas) construida en **Laravel 12 + Vue 3 + Tailwind CSS 3**.

---

## Stack

| Capa | Tecnología |
|---|---|
| Backend | Laravel 12, PHP 8.2 |
| Frontend (admin) | Vue 3 (Options API), Vue Router 4 |
| Bundler | Vite 6 + laravel-vite-plugin |
| CSS | Tailwind CSS 3 + @tailwindcss/forms + @tailwindcss/typography |
| Editor de texto | Quill 2.x (tiene bug en 2.x: `getSelection()` falla cuando pierde el foco — blot is null) |
| Comentarios | lakm/laravel-comments 4.x |
| PDF | barryvdh/laravel-dompdf |
| Auth | Laravel Breeze |

### Comandos habituales

```bash
# Compilar assets
npm run build

# Limpiar cachés después de cambios en rutas/vistas
php artisan route:clear
php artisan view:clear
php artisan config:clear

# Migraciones
php artisan migrate
```

### CRÍTICO: Tailwind + Vue

`tailwind.config.js` **debe** incluir `./resources/js/**/*.vue` en el array `content`, de lo contrario las clases usadas solo en componentes Vue son purgadas del CSS de producción.

```js
content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/js/**/*.vue',   // <- OBLIGATORIO
    './resources/css/**/*.css',
],
```

---

## Modelos

| Modelo | Tabla | Campos destacados | Relaciones |
|---|---|---|---|
| `Revista` | revistas | titulo, slug, fecha_publicacion, descripcion, portada | hasMany(Articulo) |
| `Articulo` | articulos | revista_id, columnista_id, titulo, slug, contenido | belongsTo(Revista), belongsTo(Columnista) |
| `Columnista` | columnistas | nombre, email, foto, bio, participa_proximo_numero | hasMany(Articulo) |
| `Editorial` | editorials | revista_id, titulo, slug, contenido | belongsTo(Revista) |
| `Entrevista` | entrevistas | titulo, slug, entrevistado, cargo, contenido, imagen, imagen_desktop, fecha_publicacion | — |
| `CableATierra` | cable_a_tierra | titulo, slug, autor, resumen, contenido, imagen, imagen_desktop, **video_youtube**, fecha_publicacion | — |
| `Noticia` | noticias | titulo, slug, resumen, cuerpo, imagen, fecha_publicacion | — |
| `Denuncia` | denuncias | titulo, slug, nombre, descripcion, ubicacion, imagen1-4, estado (enum), approved_at | — |
| `Atractivo` | atractivos | user_id, categoria_id, title, description, tags(json), image, galeria(json), ciudad, lat, lng, horario | belongsTo(User), belongsTo(Categoria) |
| `Categoria` | categorias | nombre, slug, icono, descripcion | hasMany(Atractivo) |
| `Suscriptor` | suscriptores | email (unique) | — |
| `Contacto` | contactos | nombre, apellido, correo, telefono, motivo | — |
| `PdfTracking` | pdf_tracking | pdf_name, action, ip_address, user_agent, referer | — |
| `User` | users | name, email, password | — |

---

## Rutas Web principales (`routes/web.php`)

```
GET  /                          InicioController@inicio
GET  /noticias                  NoticiaController@noticiasIndex
GET  /noticia/{slug}            NoticiaController@showBySlug
GET  /editoriales               InicioController@editoriales
GET  /editorial/{slug}          InicioController@showEditorial
GET  /entrevistas               InicioController@entrevistas
GET  /entrevista/{slug}         InicioController@showEntrevista
GET  /cable-a-tierra            InicioController@cableATierra
GET  /cable-a-tierra/{slug}     InicioController@showCableATierra
GET  /revistas-lista            InicioController@revistas
GET  /revista/{slug}            InicioController@showRevista
GET  /denuncias                 DenunciaController@index
GET  /denuncia                  DenunciaController@formulario
POST /denuncia                  DenunciaController@store
GET  /denuncias/{denuncia}      DenunciaController@show
GET  /buscar                    BusquedaController@buscar
POST /newsletter                NewsletterController@subscribe
GET  /pdf/{pdfName}/{action}    PdfTrackingController@track
GET  /dashboard-vue/{any?}      → dashboard-vue.blade.php (SPA Vue)
```

---

## Rutas API principales (`routes/api.php`)

Cada recurso tiene: `GET /`, `POST /`, `GET /{id}`, `PUT /{id}`, `DELETE /{id}`

```
/api/revistas
/api/articulos
/api/columnistas
/api/editoriales
/api/noticias
/api/entrevistas
/api/cable-a-tierra
/api/cable-a-tierra/upload-imagen   POST  (debe ir ANTES de /{id})
/api/atractivos
/api/denuncias
/api/denuncias/{id}/aprobar         POST
/api/denuncias/{id}/rechazar        POST
/api/categorias
```

> **IMPORTANTE:** Rutas específicas como `/upload-imagen` deben definirse **antes** del wildcard `/{cableATierra}` en el grupo, o Laravel las intercepta como un ID de recurso y devuelve 405.

---

## Controladores API

| Controlador | Métodos especiales |
|---|---|
| `RevistaApiController` | CRUD estándar |
| `ArticuloApiController` | CRUD estándar |
| `ColumnistaApiController` | + `getAvailableImages()` |
| `EditorialApiController` | CRUD estándar |
| `NoticiaApiController` | CRUD estándar |
| `EntrevistaApiController` | CRUD estándar |
| `CableATierraApiController` | + `uploadImagen()` — valida imagen ≤5MB, guarda en `cable-a-tierra/contenido` |
| `AtractivoApiController` | + `store2()`, filtros GPS |
| `DenunciaApiController` | + `aprobar()`, `rechazar()` |
| `CategoriaApiController` | solo `store()` |

---

## Componentes Vue (Dashboard Admin)

El dashboard admin es una SPA Vue servida desde `/dashboard-vue`. Vue Router maneja las secciones internas.

| Componente | Función |
|---|---|
| `DashboardVue.vue` | Stats, menú principal, router |
| `DashboardLayout.vue` | Layout wrapper |
| `RevistasManager.vue` + `RevistasForm.vue` | CRUD revistas |
| `ArticulosManager.vue` + `ArticulosForm.vue` + `ArticulosTable.vue` | CRUD artículos |
| `EditorialesManager.vue` + `EditorialesForm.vue` | CRUD editoriales |
| `EntrevistasManager.vue` + `EntrevistasForm.vue` | CRUD entrevistas |
| `ColumnistasManager.vue` + `ColumnistasForm.vue` + `ColumnistasShow.vue` | CRUD columnistas |
| `NoticiasManager.vue` + `NoticiasForm.vue` | CRUD noticias |
| `CableATierraManager.vue` + `CableATierraForm.vue` | CRUD Cable a Tierra |
| `AtractivosManager.vue` + `AtractivosForm.vue` | CRUD atractivos con GPS |
| `DenunciasManager.vue` | Gestión de denuncias (aprobar/rechazar) |
| `CategoriaForm.vue` | Crear categorías |

### CableATierraForm.vue — arquitectura especial

Quill 2.x tiene un bug que hace crash al insertar imágenes via API cuando pierde el foco. La solución implementada:
- Quill solo maneja texto (sin botón de imagen en toolbar)
- `imagenesContenido[]` — array separado: `{archivo, preview, posicion, url}`
- `cantidadParrafos` computed: cuenta `</p>`, `</h[1-6]>`, `</li>` en `form.contenido`
- `buildContenidoFinal()`: mezcla texto Quill + imágenes en los párrafos indicados al guardar
- `submitForm()`: sube imágenes pendientes → construye HTML final → envía FormData

---

## Vistas Blade

```
resources/views/
├── components/          header, navbar, footer, apoyanos, apoyo-button, forms/*
├── inicio/
│   ├── inicio.blade.php                ← Home: Destacada + último Cable a Tierra
│   ├── articulo.blade.php              ← Detalle de columna
│   ├── cable-a-tierra.blade.php        ← Listado Cable a Tierra
│   ├── cable-a-tierra-detalle.blade.php ← Detalle (con YouTube embed)
│   ├── editorial.blade.php / editoriales.blade.php
│   ├── entrevista.blade.php / entrevistas.blade.php
│   ├── revista-detalle.blade.php       ← Detalle revista (PDF hardcodeado para slug "especial-paseo-wheelwright...")
│   └── revistas.blade.php
├── admin/               columnistas, noticias, suscriptores
├── auth/                login, register, etc.
├── denuncia/            formulario, show, index, gracias
├── juegos/              trivia, wordle, crucigrama, sopa de letras
├── labrujula/           index, panoramas, show (atractivos con mapa)
├── noticias.blade.php
├── noticia-detalle.blade.php
├── dashboard-vue.blade.php  ← Punto de entrada SPA admin
└── limpiador-correos.blade.php ← Tool interna: elimina emails del listado por lote
```

---

## Decisiones de diseño conocidas

### Colores de marca
- Rojo principal: `#fc5648`
- Amarillo: `#eba81d`
- Hover rojo: `#d94439`

### Redirección post-registro
`RegisteredUserController` redirige a `/dashboard-vue` directamente (no `route('dashboard')`, que no existe).

### PDF especial
`revista-detalle.blade.php` tiene el link de descarga del PDF hardcodeado para el slug `especial-paseo-wheelwright-entre-el-potencial-y-el-olvido`. No hay campo `pdf_url` en la base de datos (es una sola edición especial).

### YouTube en Cable a Tierra
Regex que soporta todos los formatos:
```php
preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $url, $m);
```
Formatos soportados: `watch?v=`, `/embed/`, `/shorts/`, `youtu.be/`

### Storage
Imágenes en disco `public`. Paths guardados relativos (sin `/storage/`), se sirven con `asset('storage/' . $path)`.

| Tipo | Carpeta |
|---|---|
| Portadas revistas | `portadas/` |
| Cable a Tierra (portada) | `cable-a-tierra/` |
| Cable a Tierra (contenido) | `cable-a-tierra/contenido/` |
| Entrevistas | `entrevistas/` |
| Atractivos | (variable) |

### Caché de rutas
Después de agregar/modificar rutas en `api.php` o `web.php`, ejecutar:
```bash
php artisan route:clear
```

---

## Secciones del sitio público

| Sección | URL | Descripción |
|---|---|---|
| Home | `/` | Destacada + último Cable a Tierra |
| Noticias | `/noticias` | Listado de noticias |
| Revista | `/revistas-lista` | Listado de ediciones |
| Editoriales | `/editoriales` | Editoriales por revista |
| Entrevistas | `/entrevistas` | Entrevistas |
| Cable a Tierra | `/cable-a-tierra` | Columnas con imagen y video YouTube |
| La Brújula | `/labrujula` | Mapa de atractivos de Valparaíso |
| Denuncias | `/denuncias` | Denuncias ciudadanas |
| Juegos | `/juegos` | Trivia, Wordle, Crucigrama |
| Búsqueda | `/buscar` | Búsqueda en todos los contenidos |
