<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'utilisateur_id', 'ticket_id', 'qr_code', 'montant',
        'methode', 'statut', 'reference',
    ];
    
    public function utilisateur(){
        return $this->belongsTo(Utilisateur::class);
    }
    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function ticket(){
        return $this->belongsTo(Ticket::class);
    } 
}
