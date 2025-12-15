<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LakM\Commenter\Concerns\Commentable;
use LakM\Commenter\Contracts\CommentableContract;

class Entrevista extends Model implements CommentableContract
{
    use HasFactory;
    use Commentable;
    private $guestMode = true;

    protected $fillable = [
        'titulo',
        'slug',
        'entrevistado',
        'cargo',
        'contenido',
        'imagen',
        'imagen_desktop',
        'fecha_publicacion'
    ];

    protected $casts = [
        'fecha_publicacion' => 'date:Y-m-d',
    ];
}
