<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LakM\Commenter\Concerns\Commentable;
use LakM\Commenter\Contracts\CommentableContract;


class Articulo extends Model implements CommentableContract
{
    use HasFactory;
    use Commentable;
    private $guestMode = true;

    protected $fillable = [
        'revista_id',
        'titulo',
        'slug',
        'contenido',
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

    public function columnista()
    {
        return $this->belongsTo(Columnista::class);
    }
}
