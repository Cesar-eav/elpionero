<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atractivo extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'tags',
        'image',
        'ciudad',
        'enlace',
        'autor',
        'lng',
        'lat',
        'horario',
    ];

    protected $casts = [
        'tags' => 'array',
        'lng' => 'float',
        'lat' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesor: Procesa los tags para asegurar que sean un array limpio
     */
    public function getProcessedTagsAttribute()
    {
        if (!$this->tags) {
            return [];
        }

        $tags = is_array($this->tags) ? $this->tags : (is_string($this->tags) ? json_decode($this->tags, true) : []);
        
        return array_map(fn($tag) => trim((string)$tag, ' "[]'), $tags ?? []);
    }

    /**
     * Accesor: Asegura que el enlace tenga protocolo
     */
    public function getEnlaceAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // Si no empieza con http:// o https://, agregar https://
        if (!str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
            return 'https://' . $value;
        }

        return $value;
    }

    /**
     * Relación: Un atractivo pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

