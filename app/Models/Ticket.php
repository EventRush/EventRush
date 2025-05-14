<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['event_id', 'type',  'prix', 'image', 'quantité_disponible', 'quantite_restante', 'date_limite_vente'];

    protected $casts = [ 'prix' => 'float',
];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function utilisateur()  {
        return $this->belongsTo(Utilisateur::class);        
    }

    public function billets()
    {
        return $this->hasMany(Billet::class);
    }
}
