<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approvisionnement extends Model
{
     protected $fillable = [
        'produit_id',
        'quantite',
        'prix_unitaire',
        'total',
        'date'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
