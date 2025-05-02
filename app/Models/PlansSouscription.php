<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlansSouscription extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'prix', 'duree_jours'];
    protected $casts = [ 'prix' => 'float',
];

    public function souscriptions()
    {
        return $this->hasMany(Souscription::class);
    }

}
