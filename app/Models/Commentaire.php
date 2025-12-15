<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id', 'utilisateur_id', 'commentable_id', 'commentable_type',  'contenu', 'note'
    ];
    // public function event()
    // {
    //     return $this->belongsTo(Event::class);
    // }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
        
    }
    public function commentable()
    {
        return $this->morphTo();
    }

}
