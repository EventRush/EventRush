<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventVue extends Model
{
    use HasFactory;
        
    protected $fillable = ['utilisateur_id', 'event_id'];

}
