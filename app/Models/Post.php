<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LakM\Commenter\Concerns\Commentable;
use LakM\Commenter\Contracts\CommentableContract;

class Post extends Model implements CommentableContract // <-- AGREGAR
{
    use Commentable; // <-- AGREGAR

    // ... otros métodos y propiedades del modelo
}