<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilisateurBadge extends Model
{
    use HasFactory;
     protected $table = 'utilisateur_badges';

    protected $fillable = [
        'utilisateur_id',
        'badge_id',
    ];
}
