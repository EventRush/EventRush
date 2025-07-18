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
        'avatar', 'role', 'otp', 'otp_expires_at', 'google_id', 'points', 'statut_compte'
    ];

     protected $hidden = [
        'password',
    ];


      // ✅ Les statuts disponibles
    public const STATUT_ACTIF = 'actif';
    public const STATUT_INACTIF = 'inactif';
    public const STATUT_SUSPENDU = 'suspendu';
    public const STATUT_BANNI = 'banni';
    public const STATUT_SUPPRIME = 'supprimé';

    // ✅ Optionnel : liste complète
    public const STATUTS = [
        self::STATUT_ACTIF,
        self::STATUT_INACTIF,
        self::STATUT_SUSPENDU,
        self::STATUT_BANNI,
        self::STATUT_SUPPRIME,
    ];

   

    // ✅ Petite méthode utile pour la lisibilité
    public function estActif(): bool
    {
        return $this->statut_compte === self::STATUT_ACTIF;
    }

    public function estSuspendu(): bool
    {
        return $this->statut_compte === self::STATUT_SUSPENDU;
    }

    public function estBanni(): bool
    {
        return $this->statut_compte === self::STATUT_BANNI;
    }
    
   
    public function organisateurProfil(){
        return $this->hasOne(OrganisateurProfile::class);
    }

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
        return $this->belongsToMany(Utilisateur::class, 'suivis', 'suivi_id', 'utilisateur_id');
    }

    public function suivis()
    {
        return $this->belongsToMany( Utilisateur::class, 'suivis',   'utilisateur_id', 'suivi_id');
    }

    public function billets()
    {
        return $this->hasMany(Billet::class);
    }
    public function eventforScanneur()
    {
        return $this->belongsToMany(Event::class,'event_scanneurs', 'utilisateur_id', 'event_id' );
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'utilisateur_tag', 'utilisateur_id', 'tag_id');
    }



}
