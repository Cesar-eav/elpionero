<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrevista extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'slug',
        'entrevistado',
        'cargo',
        'contenido',
        'imagen',
        'fecha_publicacion'
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
    ];
}
