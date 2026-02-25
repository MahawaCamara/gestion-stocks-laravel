<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Vente extends Model
{
    protected $fillable = [
    'numero_facture',
    'user_id',
    'montant_total',
    'date',
   ];
     protected $casts = [
    'date' => 'date',
    ];

    public function produit() {
        return $this->belongsTo(Produit::class);
    }
    public function ligneVentes()
    {
        return $this->hasMany(LigneVente::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function lignes()
    {
    return $this->hasMany(LigneVente::class);
    }

}
