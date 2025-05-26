<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suivi extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'suivi_id',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function suivi()
    {
        return $this->belongsTo(Utilisateur::class);
    }

}
