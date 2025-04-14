<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id', 'order_id', 'souscription_id', 'montant',
        'methode', 'statut', 'reference',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function souscription()
    {
        return $this->belongsTo(Souscription::class);
    }


}
