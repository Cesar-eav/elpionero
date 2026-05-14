<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denuncia enviada - El Pionero de Valparaíso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('components.navbar')

    <div class="max-w-2xl mx-auto px-4 py-20 text-center">
        <div class="bg-white rounded-2xl shadow-lg p-10">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-3">¡Denuncia recibida!</h1>
            <p class="text-gray-600 mb-6">
                Gracias por enviarnos tu denuncia. Nuestro equipo editorial la revisará y, si cumple con los criterios editoriales, será publicada.
            </p>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-8 flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-amber-800 text-sm">
                    El tiempo máximo de aprobación es de <strong>24 horas</strong>.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a
                    href="{{ route('denuncia.index') }}"
                    class="bg-[#fc5648] hover:bg-red-600 text-white font-medium py-2.5 px-6 rounded-lg transition-colors"
                >
                    Ver denuncias publicadas
                </a>
                <a
                    href="{{ route('inicio') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-colors"
                >
                    Ir al inicio
                </a>
            </div>
        </div>
    </div>
</body>
</html>
