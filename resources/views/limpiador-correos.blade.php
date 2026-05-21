<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Limpiador de Correos - El Pionero de Valparaíso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen p-4">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Limpiador de Correos Electrónicos</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna izquierda: Entrada -->
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="input-emails" class="block text-sm font-semibold text-gray-700">
                                Pega tus correos aquí:
                            </label>
                            <span class="text-sm text-gray-600">
                                Correos detectados: <span id="input-count" class="font-bold text-blue-600">0</span>
                            </span>
                        </div>
                        <textarea
                            id="input-emails"
                            rows="15"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm resize-y"
                            placeholder="Pega aquí los correos en cualquier formato:&#10;&#10;ejemplo@mail.com&#10;Juan Pérez <juan@mail.com>&#10;maria@mail.com, pedro@mail.com&#10;<contacto@empresa.cl>&#10;&#10;✓ Sin límite de cantidad&#10;✓ Soporta miles de correos"
                            oninput="actualizarContadorEntrada()"></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button
                            onclick="limpiarCorreos()"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                            Limpiar y Extraer Correos
                        </button>
                        <button
                            onclick="limpiarTodo()"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                            Limpiar Todo
                        </button>
                    </div>

                    <!-- Sección de correos gubernamentales -->
                    <div id="correos-gubernamentales" class="mt-4 bg-amber-50 border border-amber-300 rounded-lg p-4" style="display: none;">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-gray-800">
                                🏛️ Correos Gubernamentales: <span id="count-gubernamentales" class="text-amber-700">0</span>
                            </h3>
                            <button
                                onclick="copiarGubernamentales()"
                                class="text-sm bg-amber-600 hover:bg-amber-700 text-white font-semibold py-1.5 px-3 rounded transition-colors">
                                📋 Copiar
                            </button>
                        </div>
                        <textarea
                            id="lista-gubernamentales"
                            readonly
                            rows="8"
                            class="w-full px-3 py-2 border border-amber-200 rounded bg-white font-mono text-xs resize-y"></textarea>
                        <div class="mt-2 text-xs text-gray-600">
                            Dominios detectados: .gob.cl, .gov.cl, .presidencia.cl, .congreso.cl, y otros organismos públicos
                        </div>
                    </div>

                    <!-- Sección para eliminar correos -->
                    <div id="seccion-eliminar" class="mt-4 bg-red-50 border border-red-300 rounded-lg p-4" style="display: none;">
                        <h3 class="font-semibold text-gray-800 mb-2">
                            🗑️ Eliminar correos del listado
                        </h3>
                        <div class="space-y-2">
                            <textarea
                                id="input-eliminar"
                                rows="4"
                                placeholder="Pega uno o varios correos a eliminar (uno por línea, o separados por coma/espacio)..."
                                class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm font-mono resize-y"></textarea>
                            <button
                                onclick="eliminarCorreo()"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                                Eliminar del listado
                            </button>
                        </div>
                        <div id="correos-eliminados-container" class="mt-3" style="display: none;">
                            <div class="text-xs text-gray-700 font-semibold mb-1">
                                Correos eliminados: <span id="count-eliminados" class="text-red-600">0</span>
                            </div>
                            <div id="lista-eliminados" class="flex flex-wrap gap-1"></div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Resultados -->
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Correos únicos encontrados: <span id="email-count" class="text-blue-600 font-bold">0</span>
                            </label>
                            <div class="flex gap-2" id="botones-acciones" style="display: none;">
                                <button
                                    onclick="descargarExcel()"
                                    class="text-sm bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded transition-colors">
                                    📊 Descargar Excel
                                </button>
                                <button
                                    onclick="copiarTodos()"
                                    class="text-sm bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition-colors">
                                    📋 Copiar Todos
                                </button>
                            </div>
                        </div>

                        <!-- Contenedor de grupos de 100 -->
                        <div id="grupos-container" class="space-y-3 max-h-[600px] overflow-y-auto">
                            <div class="text-center text-gray-500 py-8">
                                Los correos únicos aparecerán aquí agrupados de 100 en 100
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">Formatos compatibles:</h3>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>✓ ejemplo@mail.com</li>
                            <li>✓ Nombre Apellido &lt;correo@mail.com&gt;</li>
                            <li>✓ correo1@mail.com, correo2@mail.com</li>
                            <li>✓ &lt;contacto@empresa.cl&gt;</li>
                            <li>✓ Cualquier mezcla de los anteriores</li>
                        </ul>
                        <div class="mt-3 pt-3 border-t border-blue-300">
                            <p class="text-xs text-gray-600 mb-2">
                                💡 <strong>Grupos de 100:</strong> Los resultados se dividen automáticamente en grupos de 100 correos para facilitar su gestión.
                            </p>
                            <p class="text-xs text-gray-600">
                                📊 <strong>Excel:</strong> Descarga todos los correos únicos en formato CSV compatible con Excel.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas adicionales -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="text-sm text-gray-600">Total procesados</div>
                    <div class="text-2xl font-bold text-gray-900" id="total-procesados">0</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="text-sm text-gray-600">Únicos</div>
                    <div class="text-2xl font-bold text-green-600" id="total-unicos">0</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="text-sm text-gray-600">Duplicados eliminados</div>
                    <div class="text-2xl font-bold text-red-600" id="total-duplicados">0</div>
                </div>
            </div>
        </div>

        <!-- Botón para volver -->
        <div class="mt-6 text-center">
            <a href="{{ route('inicio') }}" class="inline-block text-blue-600 hover:text-blue-800 font-medium">
                ← Volver al inicio
            </a>
        </div>
    </div>

    <script>
        function extraerCorreos(texto) {
            // Expresión regular para extraer correos electrónicos
            const regexEmail = /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/g;
            const correos = texto.match(regexEmail) || [];
            return correos;
        }

        function actualizarContadorEntrada() {
            const inputText = document.getElementById('input-emails').value;
            const correos = extraerCorreos(inputText);
            document.getElementById('input-count').textContent = correos.length.toLocaleString();

            // Detectar correos gubernamentales en tiempo real
            detectarCorreosGubernamentales(correos);
        }

        function esCorreoGubernamental(email) {
            const dominiosGubernamentales = [
                '.gob.cl',
                '.gov.cl',
                '.presidencia.cl',
                '.congreso.cl',
                '.senado.cl',
                '.camara.cl',
                '.ejercito.cl',
                '.armada.cl',
                '.fach.cl',
                '.carabineros.cl',
                '.pdi.cl',
                '.interior.gob.cl',
                '.minsal.cl',
                '.mineduc.cl',
                '.minjusticia.cl',
                '.minrel.gob.cl',
                '.mop.gob.cl',
                '.minvu.gob.cl',
                '.economia.gob.cl',
                '.hacienda.cl',
                '.sii.cl',
                '.tesoreria.cl',
                '.suseso.cl',
                '.chileatiende.gob.cl',
                '.serviciocivil.cl',
                '.registrocivil.cl',
                '.consejodefensa.cl',
                '.contraloria.cl',
                '.tcchile.cl',
                '.tribunalconstitucional.cl',
                '.pjud.cl',
                '.poderjudicial.cl',
                '.defensoria.cl',
                '.fiscaliadechile.cl'
            ];

            const emailLower = email.toLowerCase();
            return dominiosGubernamentales.some(dominio => emailLower.includes(dominio));
        }

        function detectarCorreosGubernamentales(correos) {
            const correosGob = correos.filter(email => esCorreoGubernamental(email));
            const correosGobUnicos = [...new Set(correosGob.map(email => email.toLowerCase()))].sort();

            const seccionGob = document.getElementById('correos-gubernamentales');
            const listaGob = document.getElementById('lista-gubernamentales');
            const countGob = document.getElementById('count-gubernamentales');

            if (correosGobUnicos.length > 0) {
                seccionGob.style.display = 'block';
                listaGob.value = correosGobUnicos.join('\n');
                countGob.textContent = correosGobUnicos.length;
            } else {
                seccionGob.style.display = 'none';
                listaGob.value = '';
                countGob.textContent = '0';
            }
        }

        function copiarGubernamentales() {
            const texto = document.getElementById('lista-gubernamentales').value;
            if (!texto.trim()) {
                alert('No hay correos gubernamentales para copiar.');
                return;
            }

            navigator.clipboard.writeText(texto).then(() => {
                const count = texto.split('\n').filter(l => l.trim()).length;
                mostrarNotificacion(`${count} correo(s) gubernamental(es) copiado(s) al portapapeles`, 'success');
            }).catch(err => {
                alert('Error al copiar: ' + err);
            });
        }

        let correosGlobales = [];
        let correosEliminados = [];

        function limpiarCorreos() {
            const inputText = document.getElementById('input-emails').value;

            if (!inputText.trim()) {
                alert('Por favor, pega algunos correos primero.');
                return;
            }

            // Mostrar indicador de procesamiento para grandes volúmenes
            const botonProcesar = event.target;
            const textoOriginal = botonProcesar.textContent;
            botonProcesar.textContent = 'Procesando...';
            botonProcesar.disabled = true;

            // Usar setTimeout para permitir que la UI se actualice
            setTimeout(() => {
                try {
                    // Extraer todos los correos del texto
                    const correosEncontrados = extraerCorreos(inputText);

                    // Convertir a minúsculas y eliminar duplicados usando Set (muy eficiente)
                    const correosUnicos = [...new Set(correosEncontrados.map(email => email.toLowerCase()))];

                    // Ordenar alfabéticamente
                    correosUnicos.sort();

                    // Guardar en variable global
                    correosGlobales = correosUnicos;

                    // Aplicar correos eliminados si existen
                    aplicarCorreosEliminados();

                    // Crear grupos de 100
                    mostrarGrupos(correosGlobales);

                    // Actualizar estadísticas
                    const totalProcesados = correosEncontrados.length;
                    const totalUnicos = correosGlobales.length;
                    const totalDuplicados = totalProcesados - totalUnicos - correosEliminados.length;

                    document.getElementById('email-count').textContent = totalUnicos.toLocaleString();
                    document.getElementById('total-procesados').textContent = totalProcesados.toLocaleString();
                    document.getElementById('total-unicos').textContent = totalUnicos.toLocaleString();
                    document.getElementById('total-duplicados').textContent = totalDuplicados.toLocaleString();

                    // Mostrar botones de acciones
                    document.getElementById('botones-acciones').style.display = 'flex';

                    // Mostrar sección de eliminar
                    document.getElementById('seccion-eliminar').style.display = 'block';

                    // Mostrar mensaje de éxito
                    if (totalDuplicados > 0) {
                        mostrarNotificacion(`Se eliminaron ${totalDuplicados.toLocaleString()} correo(s) duplicado(s)`, 'success');
                    } else {
                        mostrarNotificacion('No se encontraron duplicados', 'info');
                    }
                } catch (error) {
                    alert('Error al procesar los correos: ' + error.message);
                } finally {
                    // Restaurar botón
                    botonProcesar.textContent = textoOriginal;
                    botonProcesar.disabled = false;
                }
            }, 50);
        }

        function mostrarGrupos(correos) {
            const container = document.getElementById('grupos-container');
            container.innerHTML = '';

            const tamanioGrupo = 100;
            const totalGrupos = Math.ceil(correos.length / tamanioGrupo);

            for (let i = 0; i < totalGrupos; i++) {
                const inicio = i * tamanioGrupo;
                const fin = Math.min((i + 1) * tamanioGrupo, correos.length);
                const grupoCorreos = correos.slice(inicio, fin);

                const grupoDiv = document.createElement('div');
                grupoDiv.className = 'border border-gray-300 rounded-lg p-4 bg-white';

                grupoDiv.innerHTML = `
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-semibold text-gray-800">
                            Grupo ${i + 1} de ${totalGrupos}
                            <span class="text-sm font-normal text-gray-600">(${inicio + 1} - ${fin})</span>
                        </h3>
                        <button
                            onclick="copiarGrupo(${i})"
                            class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1.5 px-3 rounded transition-colors">
                            📋 Copiar estos 100
                        </button>
                    </div>
                    <textarea
                        id="grupo-${i}"
                        readonly
                        rows="5"
                        class="w-full px-3 py-2 border border-gray-200 rounded bg-gray-50 font-mono text-xs resize-y"
                    >${grupoCorreos.join('\n')}</textarea>
                    <div class="mt-2 text-xs text-gray-500">
                        ${grupoCorreos.length} correo(s) en este grupo
                    </div>
                `;

                container.appendChild(grupoDiv);
            }
        }

        function copiarGrupo(indice) {
            const textarea = document.getElementById(`grupo-${indice}`);
            const texto = textarea.value;

            navigator.clipboard.writeText(texto).then(() => {
                mostrarNotificacion(`Grupo ${indice + 1} copiado al portapapeles`, 'success');
            }).catch(err => {
                alert('Error al copiar: ' + err);
            });
        }

        function copiarTodos() {
            if (correosGlobales.length === 0) {
                alert('No hay correos para copiar.');
                return;
            }

            const texto = correosGlobales.join('\n');
            navigator.clipboard.writeText(texto).then(() => {
                mostrarNotificacion(`${correosGlobales.length.toLocaleString()} correos copiados al portapapeles`, 'success');
            }).catch(err => {
                alert('Error al copiar: ' + err);
            });
        }

        function descargarExcel() {
            if (correosGlobales.length === 0) {
                alert('No hay correos para descargar.');
                return;
            }

            // Crear contenido CSV (compatible con Excel)
            let csvContent = '\ufeff'; // BOM para UTF-8
            csvContent += 'Correo Electrónico\n'; // Encabezado

            // Agregar cada correo en una fila
            correosGlobales.forEach(correo => {
                csvContent += correo + '\n';
            });

            // Crear blob y descargar
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            // Generar nombre de archivo con fecha
            const fecha = new Date().toISOString().split('T')[0];
            const nombreArchivo = `correos_unicos_${fecha}.csv`;

            link.setAttribute('href', url);
            link.setAttribute('download', nombreArchivo);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            mostrarNotificacion(`Archivo Excel descargado: ${nombreArchivo}`, 'success');
        }

        function limpiarTodo() {
            document.getElementById('input-emails').value = '';
            document.getElementById('input-count').textContent = '0';
            document.getElementById('email-count').textContent = '0';
            document.getElementById('total-procesados').textContent = '0';
            document.getElementById('total-unicos').textContent = '0';
            document.getElementById('total-duplicados').textContent = '0';
            document.getElementById('botones-acciones').style.display = 'none';

            const container = document.getElementById('grupos-container');
            container.innerHTML = '<div class="text-center text-gray-500 py-8">Los correos únicos aparecerán aquí agrupados de 100 en 100</div>';

            // Limpiar sección gubernamental
            document.getElementById('correos-gubernamentales').style.display = 'none';
            document.getElementById('lista-gubernamentales').value = '';
            document.getElementById('count-gubernamentales').textContent = '0';

            // Limpiar sección eliminar
            document.getElementById('seccion-eliminar').style.display = 'none';
            document.getElementById('input-eliminar').value = '';
            document.getElementById('correos-eliminados-container').style.display = 'none';
            document.getElementById('lista-eliminados').innerHTML = '';
            document.getElementById('count-eliminados').textContent = '0';

            correosGlobales = [];
            correosEliminados = [];
        }

        function eliminarCorreo() {
            const input = document.getElementById('input-eliminar');
            const textoIngresado = input.value.trim();

            if (!textoIngresado) {
                alert('Por favor, ingresa uno o más correos para eliminar.');
                return;
            }

            // Extraer todos los correos del texto ingresado
            const regexEmail = /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/g;
            const correosDetectados = textoIngresado.match(regexEmail) || [];
            const correosAEliminar = [...new Set(correosDetectados.map(e => e.toLowerCase()))];

            if (correosAEliminar.length === 0) {
                mostrarNotificacion('No se detectaron correos válidos en el texto ingresado', 'info');
                return;
            }

            let eliminados = 0;
            correosAEliminar.forEach(correo => {
                const indice = correosGlobales.indexOf(correo);
                if (indice !== -1) {
                    correosGlobales.splice(indice, 1);
                    eliminados++;
                }
                if (!correosEliminados.includes(correo)) {
                    correosEliminados.push(correo);
                }
            });

            // Actualizar la vista
            mostrarGrupos(correosGlobales);
            actualizarListaEliminados();
            actualizarEstadisticas();

            // Limpiar textarea
            input.value = '';

            if (eliminados > 0) {
                mostrarNotificacion(`${eliminados} correo(s) eliminado(s) del listado`, 'success');
            } else {
                mostrarNotificacion(`Ninguno de los ${correosAEliminar.length} correo(s) estaba en el listado`, 'info');
            }
        }

        function actualizarListaEliminados() {
            const container = document.getElementById('correos-eliminados-container');
            const lista = document.getElementById('lista-eliminados');
            const count = document.getElementById('count-eliminados');

            if (correosEliminados.length > 0) {
                container.style.display = 'block';
                count.textContent = correosEliminados.length;

                lista.innerHTML = '';
                correosEliminados.forEach((correo, index) => {
                    const badge = document.createElement('span');
                    badge.className = 'inline-flex items-center gap-1 bg-red-100 text-red-800 text-xs px-2 py-1 rounded';
                    badge.innerHTML = `
                        ${correo}
                        <button onclick="restaurarCorreo(${index})" class="hover:text-red-900 font-bold" title="Restaurar">
                            ↺
                        </button>
                    `;
                    lista.appendChild(badge);
                });
            } else {
                container.style.display = 'none';
            }
        }

        function restaurarCorreo(index) {
            const correo = correosEliminados[index];

            // Eliminar de la lista de eliminados
            correosEliminados.splice(index, 1);

            // Agregar de vuelta a la lista global y ordenar
            correosGlobales.push(correo);
            correosGlobales.sort();

            // Actualizar la vista
            mostrarGrupos(correosGlobales);
            actualizarListaEliminados();
            actualizarEstadisticas();

            mostrarNotificacion('Correo restaurado', 'success');
        }

        function aplicarCorreosEliminados() {
            if (correosEliminados.length > 0) {
                correosGlobales = correosGlobales.filter(correo => !correosEliminados.includes(correo));
            }
        }

        function actualizarEstadisticas() {
            document.getElementById('email-count').textContent = correosGlobales.length.toLocaleString();
            document.getElementById('total-unicos').textContent = correosGlobales.length.toLocaleString();
        }

        function mostrarNotificacion(mensaje, tipo) {
            // Crear notificación temporal
            const notificacion = document.createElement('div');
            notificacion.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-semibold transition-all z-50 ${
                tipo === 'success' ? 'bg-green-600' : 'bg-blue-600'
            }`;
            notificacion.textContent = mensaje;
            document.body.appendChild(notificacion);

            // Eliminar después de 3 segundos
            setTimeout(() => {
                notificacion.remove();
            }, 3000);
        }

        // Atajo de teclado: Ctrl+Enter para limpiar
        document.getElementById('input-emails').addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                limpiarCorreos();
            }
        });
    </script>
</body>

</html>
