<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'organisateur_id',
         'titre', 'description',
        'date_debut', 'date_fin', 'lieu', 'statut', 'affiche'
    ];

    

    public function photos()
    {
        return $this->hasMany(EventPhoto::class);
    }
}

// public function organisateur()
    // {
    //     return $this->belongsTo(OrganisateurProfile::class, 'organisateur_id');
    // }
