<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denuncia extends Model
{
    protected $fillable = [
        'titulo',
        'nombre',
        'descripcion',
        'ubicacion',
        'imagen1',
        'imagen2',
        'imagen3',
        'estado',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];
}
