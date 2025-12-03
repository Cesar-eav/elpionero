<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Articulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'revista_id',
        'titulo',
        'slug',
        'contenido',
        'autor',
        'imagen_autor',
        'seccion',
        'columnista_id'
    ];

    /**
     * Get the revista that owns the Articulo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function revista(): BelongsTo
    {
        return $this->belongsTo(Revista::class);
    }
}
