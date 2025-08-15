<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Inicio extends Model
{
    use HasFactory;

    protected $fillable = [

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
