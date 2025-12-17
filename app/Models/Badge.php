<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'icone',
        'description',
    ];

    public function utilisateurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'user_badges')
            ->withTimestamps();
    }
}
