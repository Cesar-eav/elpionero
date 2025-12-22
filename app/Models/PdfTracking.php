<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfTracking extends Model
{
    protected $table = 'pdf_tracking';

    protected $fillable = [
        'pdf_name',
        'action',
        'ip_address',
        'user_agent',
        'referer',
    ];
}
