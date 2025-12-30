<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atractivo extends Model
{
    protected $fillable = [
        'user_id',
        'categoria_id',
        'title',
        'description',
        'tags',
        'image',
        'ciudad',
        'enlace',
        'autor',
        'lng',
        'lat',
        'horario',
        'show_horario',
        'show_enlace',
    ];

    protected $casts = [
        'tags' => 'array',
        'lng' => 'float',
        'lat' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'show_horario' => 'boolean',
        'show_enlace' => 'boolean',
    ];

    /**
     * Accesor: Obtiene el nombre de la categoría desde la relación
     */
    public function getCategoryTranslatedAttribute()
    {
        return $this->categoria->nombre ?? 'Sin categoría';
    }

    public function getProcessedTagsAttribute()
    {
        if (!$this->tags) return [];
        $tags = is_array($this->tags) ? $this->tags : (is_string($this->tags) ? json_decode($this->tags, true) : []);
        return array_map(fn($tag) => trim((string)$tag, ' "[]'), $tags ?? []);
    }

    public function getEnlaceAttribute($value)
    {
        if (!$value) return null;
        if (!str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
            return 'https://' . $value;
        }
        return $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}