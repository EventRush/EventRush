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
        'nom', 'email', 'email_verified_at', 'password', 'avatar', 'role', 'otp', 'otp_espires_at',
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
        return $this->hasMany(Souscription::class);
    }
    public function souscriptionActive(){
        return $this->souscription()
        ->where('statut', 'actif')
        ->where('date_fin', '>', now())
        ->latest('date_fin')
        ->first();
    }
}
