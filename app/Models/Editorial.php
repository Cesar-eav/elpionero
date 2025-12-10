<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LakM\Commenter\Concerns\Commentable;
use LakM\Commenter\Contracts\CommentableContract;

//
class Editorial extends Model implements CommentableContract
{
    use HasFactory; 
    use Commentable;
    private $guestMode = true;

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
