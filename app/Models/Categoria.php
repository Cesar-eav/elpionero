<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'icono',
        'descripcion',
    ];

    /**
     * Relación: Una categoría tiene muchos atractivos
     */
    public function atractivos()
    {
        return $this->hasMany(Atractivo::class);
    }
}
