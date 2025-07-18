<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'billet_id', 'scanneur_id', 'initiated_at', 'token',
        'status',   'expires_at', 
    ];
}
