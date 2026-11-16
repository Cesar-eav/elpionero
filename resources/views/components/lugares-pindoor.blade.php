@props(['lugares' => []])

{{-- Lugares de Pindoor mencionados en el artículo (App\Support\Pindoor::enlazar). Sin lugares, no se muestra. --}}
@if (!empty($lugares))
    <aside class="not-prose mt-8 rounded-xl border border-blue-100 bg-blue-50 p-4 md:p-5" aria-label="Lugares mencionados en Pindoor">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-lg shrink-0" aria-hidden="true">🧭</div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-gray-900 leading-tight">Lugares mencionados en esta nota</p>
                <p class="text-xs text-gray-600 leading-tight">
                    Conócelos en <a href="{{ \App\Support\Pindoor::url() }}" target="_blank" rel="noopener" class="font-semibold text-gray-900 hover:underline">Pin<span class="text-[#fc5648]">door</span></a>, la guía de Valparaíso
                </p>
            </div>
        </div>
        <ul class="flex flex-wrap gap-2">
            @foreach ($lugares as $lugar)
                <li>
                    <a href="{{ $lugar['url'] }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-white px-3 py-1.5 text-sm font-medium text-gray-800 hover:border-[#fc5648] hover:text-[#fc5648] transition-colors">
                        <svg class="w-3.5 h-3.5 text-[#fc5648] shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        {{ $lugar['nombre'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </aside>
@endif
