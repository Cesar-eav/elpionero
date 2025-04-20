<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Artículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('articulos.update', $articulo) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="revista_id" :value="__('Revista')" />
                            <x-select-input id="revista_id" class="block mt-1 w-full" name="revista_id" required>
                                <option value="">{{ __('Seleccionar Revista') }}</option>
                                @foreach ($revistas as $revista)
                                    <option value="{{ $revista->id }}" {{ old('revista_id', $articulo->revista_id) == $revista->id ? 'selected' : '' }}>
                                        {{ $revista->titulo }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('revista_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="titulo" :value="__('Título')" />
                            <x-text-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" :value="old('titulo', $articulo->titulo)" required autofocus />
                            <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="contenido" :value="__('Contenido')" />
                            <x-forms.textarea id="descripcion" class="block mt-1 w-full" name="descripcion">
                                {{ old('descripcion') }}
                            </x-forms.textarea>
                        <x-input-error :messages="$errors->get('contenido')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="autor" :value="__('Autor (Opcional)')" />
                            <x-text-input id="autor" class="block mt-1 w-full" type="text" name="autor" :value="old('autor', $articulo->autor)" />
                            <x-input-error :messages="$errors->get('autor')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="imagen_autor" :value="__('Imagen del Autor (Opcional)')" />
                            <input id="imagen_autor" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="imagen_autor" accept="image/*">
                            @if ($articulo->imagen_autor)
                                <img src="{{ asset($articulo->imagen_autor) }}" alt="{{ $articulo->autor }}" class="rounded-full h-16 w-16 mt-2 object-cover">
                            @endif
                            <x-input-error :messages="$errors->get('imagen_autor')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="seccion" :value="__('Sección (Opcional)')" />
                            <x-text-input id="seccion" class="block mt-1 w-full" type="text" name="seccion" :value="old('seccion', $articulo->seccion)" />
                            <x-input-error :messages="$errors->get('seccion')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Actualizar Artículo') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>