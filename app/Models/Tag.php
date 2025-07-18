<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id', 'utilisateur_id', 
    ];

     public function events()
    {
        return $this->belongsToMany(Event::class, 'event_tag');
    }

    public function utilisateurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'utilisateur_tag', 'tag_id', 'utilisateur_id');
    }
}
