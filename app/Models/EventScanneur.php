<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventScanneur extends Model
{
    use HasFactory;
    
    protected $fillable = ['event_id', 'utilisateur_id']; 

    public function Utilisateur()
    {
        return $this->belongsTo(Event::class);;
    }

    public function Event()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
