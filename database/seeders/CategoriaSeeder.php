<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Monumento', 'icono' => '🗿', 'descripcion' => 'Monumentos históricos y artísticos'],
            ['nombre' => 'Cultural', 'icono' => '🎭', 'descripcion' => 'Sitios y eventos culturales'],
            ['nombre' => 'Naturaleza', 'icono' => '🌿', 'descripcion' => 'Espacios naturales y parques'],
            ['nombre' => 'Street Art', 'icono' => '🎨', 'descripcion' => 'Arte callejero y murales'],
            ['nombre' => 'Picadas', 'icono' => '🍽️', 'descripcion' => 'Restaurantes y comida local'],
            ['nombre' => 'Museos', 'icono' => '🏛️', 'descripcion' => 'Museos y galerías'],
            ['nombre' => 'Arquitectura', 'icono' => '🏗️', 'descripcion' => 'Obras arquitectónicas destacadas'],
            ['nombre' => 'Miradores', 'icono' => '👁️', 'descripcion' => 'Puntos de vista panorámicos'],
        ];

        foreach ($categorias as $cat) {
            Categoria::create([
                'nombre' => $cat['nombre'],
                'slug' => Str::slug($cat['nombre']),
                'icono' => $cat['icono'],
                'descripcion' => $cat['descripcion'],
            ]);
        }
    }
}
