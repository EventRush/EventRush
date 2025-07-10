<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisateurProfile extends Model
{
    use HasFactory;

    protected $fillable = ['utilisateur_id', 'nom_entreprise', 'description', 'logo'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    // public function events()
    // {
    //     return $this->hasMany(Event::class);
    // }

    public function souscription()
    {
        return $this->hasOne(Souscription::class);
    }

    public function suiveurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'suivis', 'organisateur_id', 'utilisateur_id');
    }


}
