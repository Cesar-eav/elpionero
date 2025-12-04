<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Columnista extends Model
{
    protected $table = 'columnistas';

    protected $fillable = [
        'nombre',
        'email',
        'foto',
        'bio',
        'participa_proximo_numero',
    ];
}
