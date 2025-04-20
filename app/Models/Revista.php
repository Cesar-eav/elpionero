<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Revista extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'slug',
        'fecha_publicacion',
        'descripcion'
    ];

    protected $casts = [
        'fecha_publicacion' => 'date:Y-m-d',
    ];

    /**
     * Get all of the articulos for the Revista
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articulos(): HasMany
    {
        return $this->hasMany(Articulo::class);
    }
}
