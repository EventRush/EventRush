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
    
    // protected $table    =   'utilisateurs';


    protected $fillable = [
        'nom', 'email', 'email_verified_at', 'password', 
        'avatar', 'role', 'otp', 'otp_expires_at', 'google_id', 'points'
    ];
    
    protected $hidden = [
        'password',
    ];

    public function organisateurProfil(){
        return $this->hasOne(OrganisateurProfile::class);
    }

    // public function order()
    // {
    //     return $this->hasMany(Order::class);
    // }

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

    public function suiveurs()
    {
        return $this->belongsToMany(Suivi::class, 'suivis', 'suivi_id', 'utilisateur_id');
    }

    public function utilisateurSuivis()
    {
        return $this->belongsToMany(Suivi::class, 'suivis',   'utilisateur_id', 'suivi_id');
    }

    public function billets()
    {
        return $this->hasMany(Billet::class);
    }
    public function eventforScanneur()
    {
        return $this->belongsToMany(Event::class,'event_scanneurs', 'utilisateur_id', 'event_id' );
    }


}
