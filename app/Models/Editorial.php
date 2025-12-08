<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Editorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'revista_id',
        'titulo',
        'slug',
        'contenido'
    ];

    /**
     * Get the revista that owns the Editorial
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function revista(): BelongsTo
    {
        return $this->belongsTo(Revista::class);
    }
}
