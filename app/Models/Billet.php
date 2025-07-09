<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'utilisateur_id', 'ticket_id', 'qr_code', 'montant',
        'methode', 'status', 'reference', 'billet_fedapay_id', 'status_scan', 'scanned_at', 'scanned_by',
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
    
    public function scanneur()
    {
        return $this->belongsTo(Utilisateur::class, 'scanned_by');
    }
    public function scanner(Utilisateur $scanneur)
    {
        if ($this->status_scan === 'scanné') {
            throw new \Exception("Ce billet a déjà été scanné.");
        }

        $this->update([
            'status_scan' => 'scanné',
            'scanned_at' => now(),
            'scanned_by' => $scanneur->id,
        ]);
    }

    public function isScanned()
    {
        return $this->status_scan === 'scanné';
    }
}
