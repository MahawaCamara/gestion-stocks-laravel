<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;

   protected $fillable = [
    'designation',
    'categorie_id',
    'description',
    'prix_vente',
    'stock_actuel',
    'seuil_alerte',
];


    public function categorie() {
        return $this->belongsTo(Categorie::class);
    }

    public function ventes() {
        return $this->hasMany(Vente::class);
    }

    public function approvisionnements() {
        return $this->hasMany(Approvisionnement::class);
    }
}


