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
    ];

    protected $casts = [
        'tags' => 'array',
        'lng' => 'float',
        'lat' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Mapeo de categorías del inglés al español
     */
    protected static $categoryTranslations = [
        'museum' => 'Museo',
        'restaurant' => 'Restaurante',
        'park' => 'Parque',
        'beach' => 'Playa',
        'viewpoint' => 'Mirador',
        'historic' => 'Sitio Histórico',
        'monument' => 'Monumento',
        'art' => 'Arte',
        'culture' => 'Cultura',
        'entertainment' => 'Entretenimiento',
        'shopping' => 'Compras',
        'nature' => 'Naturaleza',
        'sports' => 'Deportes',
        'tour' => 'Tours',
        'activity' => 'Actividad',
    ];

    /**
     * Accesor: Traduce la categoría al español
     */
    public function getCategoryTranslatedAttribute()
    {
        $categoryLower = strtolower($this->attributes['category'] ?? '');
        return self::$categoryTranslations[$categoryLower] ?? ucfirst($this->attributes['category'] ?? '');
    }

    /**
     * Método estático: Obtener todas las categorías traducidas
     */
    public static function getCategoriesTranslated()
    {
        $categories = self::distinct()->pluck('category');
        
        return $categories->map(function ($cat) {
            $catLower = strtolower($cat);
            return [
                'original' => $cat,
                'translated' => self::$categoryTranslations[$catLower] ?? ucfirst($cat)
            ];
        })->sortBy('translated')->values();
    }
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

    /**
     * Relación: Un atractivo pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}

