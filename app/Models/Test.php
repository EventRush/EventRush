<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = ['qr_code', 'status_scan', 'image',  'event_id', 'scanned_at'];

    
    public function event(){
        return $this->belongsTo(Event::class);
    }

}
