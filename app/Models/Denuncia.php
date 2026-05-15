<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Denuncia extends Model
{
    protected $fillable = [
        'titulo',
        'slug',
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

    protected static function booted(): void
    {
        static::creating(function (Denuncia $d) {
            $d->slug = static::generarSlug($d->titulo);
        });
    }

    public static function generarSlug(string $titulo): string
    {
        $base = Str::slug($titulo);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getRouteKey(): mixed
    {
        return $this->slug ?: 'denuncia-' . $this->id;
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        return static::where('slug', $value)->orWhere('id', $value)->first();
    }
}
