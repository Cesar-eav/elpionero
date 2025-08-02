<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Artículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                    <form method="POST" action="{{ route('articulos.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="revista_id" :value="__('Revista')" />
                            <x-forms.select-input id="revista_id" class="block mt-1 w-full" name="revista_id" required>
                                <option value="">{{ __('Seleccionar Revista') }}</option>
                                @foreach ($revistas as $revista)
                                    <option value="{{ $revista->id }}" {{ old('revista_id') == $revista->id ? 'selected' : '' }}>
                                        {{ $revista->titulo }}
                                    </option>
                                @endforeach
                                </x-forms.select-input>
                            <x-input-error :messages="$errors->get('revista_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="titulo" :value="__('Título')" />
                            <x-text-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" :value="old('titulo')" required autofocus />
                            <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="contenido" :value="__('Contenido')" />
                            <x-forms.textarea id="contenido" class="block mt-1 w-full" name="contenido">
                                {{ old('descripcion') }}
                            </x-forms.textarea>                            
                            <x-input-error :messages="$errors->get('contenido')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="autor" :value="__('Autor (Opcional)')" />
                            <x-text-input id="autor" class="block mt-1 w-full" type="text" name="autor" :value="old('autor')" />
                            <x-input-error :messages="$errors->get('autor')" class="mt-2" />
                        </div>

                         <div class="mt-4">
                            <x-input-label for="imagen_autor" :value="__('Imagen del Autor (Opcional)')" />
                            {{-- <file-input id="imagen_autor" class="block mt-1 w-full" name="imagen_autor" accept="image/*" /> --}}
                            <input type="file" id="imagen_autor" name="imagen_autor" accept="image/*">

                            <x-input-error :messages="$errors->get('imagen_autor')" class="mt-2" />
                        </div> 

                        <div class="mt-4">
                            <x-input-label for="seccion" :value="__('Sección (Opcional)')" />
                            <x-text-input id="seccion" class="block mt-1 w-full" type="text" name="seccion" :value="old('seccion')" />
                            <x-input-error :messages="$errors->get('seccion')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Guardar Artículo') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
</x-app-layout>