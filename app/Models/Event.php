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
        'date_debut', 'date_fin', 'lieu', 'statut', 'affiche', 
        'points', 
        'latitude', 'longitude', 
    ];

    // protected $appends = ['distance'];


    

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
    
    // public function commentaires()
    //     {
    //         return $this->hasMany(Commentaire::class);
    //     }

    public function scanneurs()
        {
            return $this->belongsToMany(Utilisateur::class, 'event_scanneurs', 'event_id', 'utilisateur_id')->where('role', 'scanneur');
        }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'event_tag');
    }

    public function scopeWithDistance($query, $lat, $lng)
{
    return $query->selectRaw("events.*, (6371 * acos(
        cos(radians(?)) *
        cos(radians(latitude)) *
        cos(radians(longitude) - radians(?)) +
        sin(radians(?)) *
        sin(radians(latitude))
    )) as distance", [$lat, $lng, $lat]);
}

    public function scopeNearLocation($query, $lat, $lng, $radius)
    {
        return $query->whereRaw("(6371 * acos(
            cos(radians(?)) *
            cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) *
            sin(radians(latitude))
        )) <= ?", [$lat, $lng, $lat, $radius]);
    }
    public function posts()
    {
        return $this->hasMany(EventPost::class);
    }

    public function commentaires()
    {
        return $this->morphMany(Commentaire::class, 'commentable');
    }
    public function shares()
    {
        return $this->morphMany(Share::class, 'shareable');
    }





}

