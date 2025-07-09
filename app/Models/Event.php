<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
         'titre', 'description',
        'date_debut', 'date_fin', 'lieu', 'statut', 'affiche', 'points'
    ];

    

    public function photos()
    {
        return $this->hasMany(EventPhoto::class);
    }
    
        public function favorisePar()
    {
        return $this->belongsToMany(Utilisateur::class, 'favoris', 'event_id', 'utilisateur_id')->withTimestamps();
    }

    public function organisateur()
        {
            return $this->belongsTo(OrganisateurProfile::class);
        }

    
    public function utilisateur()
        {
            return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
        }

        public function billets()
        {
            return $this->hasMany(Billet::class);
        }

        public function tests()
        {
            return $this->hasMany(Test::class);
        }
    
    public function tickets()
        {
            return $this->hasMany(Ticket::class);
        }

    public function scanneurs()
        {
            return $this->belongsToMany(Utilisateur::class, 'event_scanneurs', 'event_id', 'utilisateur_id')->where('role', 'scanneur');
        }

}

