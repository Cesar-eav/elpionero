<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
protected $fillable = [
    'titulo',
    'resumen',
    'cuerpo',
    'imagen',
    'fecha_publicacion',
    'slug'
];

protected $casts = [
    'fecha_publicacion' => 'date',
];


}
