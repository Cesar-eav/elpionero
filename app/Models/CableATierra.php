<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LakM\Commenter\Concerns\Commentable;
use LakM\Commenter\Contracts\CommentableContract;

class CableATierra extends Model implements CommentableContract
{
    use HasFactory;
    use Commentable;
    private $guestMode = true;

    protected $table = 'cable_a_tierra';

    protected $fillable = [
        'titulo',
        'slug',
        'autor',
        'resumen',
        'contenido',
        'imagen',
        'imagen_desktop',
        'fecha_publicacion'
    ];

    protected $casts = [
        'fecha_publicacion' => 'date:Y-m-d',
    ];
}
