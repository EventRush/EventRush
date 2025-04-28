<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suivi extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'organisateur_id',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function organisateur()
    {
        return $this->belongsTo(OrganisateurProfile::class);
    }

}
