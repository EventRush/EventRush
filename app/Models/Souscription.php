<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Souscription extends Model
{
    use HasFactory;

    protected $fillable = ['organisateur_id', 'utilisateur_id', 'type', 'date_debut', 'date_fin', 'statut'];

    public function utilisateur()  {
        return $this->belongsTo(Utilisateur::class);        
    }
    public function estActive(){
        if ($this->statut === 'actif' && 
            $this->date_fin->isPast()){
                $this->statut = 'expiré';
                $this->save();
            }

        return $this->statut === 'actif' &&
               $this->date_fin->isFuture();
    }
}
