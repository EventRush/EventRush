<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
implements MustVerifyEmail
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $utilisateur    =   "utilisateurs";

    protected $fillable = [
        'nom', 'email', 'email_verified_at', 'password', 'avatar', 'role', 'otp', 'otp_espires_at', 'google_id',
    ];

    public function organisateurProfil(){
        return $this->hasOne(OrganisateurProfile::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    protected $hidden = [
        'password',
    ];
    public function souscription(){
        return $this->hasMany(Souscription::class, 'utilisateur_id');
    }
    public function souscriptionActive(){
        return $this->souscription()
        ->where('statut', 'actif')
        ->where('date_fin', '>', now())
        ->latest('date_fin')
        ->first();
    }
    public function favoris()
    {
        return $this->belongsToMany(Event::class, 'favoris', 'utilisateur_id', 'event_id')->withTimestamps();
    }

    public function suivis()
    {
        return $this->hasMany(Suivi::class);
    }

    public function organisateursSuivis()
    {
        return $this->belongsToMany(OrganisateurProfile::class, 'suivis', 'utilisateur_id', 'organisateur_id');
    }

    public function billets()
    {
        return $this->hasMany(Billet::class);
    }


}
