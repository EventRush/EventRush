<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPost extends Model
{
    use HasFactory;

     protected $fillable = [
        'event_id',
        'utilisateur_id',
        'titre',
        'contenu',
        'image',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function shares()
    {
        return $this->morphMany(Share::class, 'shareable');
    }
}
