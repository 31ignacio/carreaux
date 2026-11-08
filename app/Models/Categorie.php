<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'couleur',
    ];

    // ==================== RELATIONS ====================

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    // ==================== HELPERS ====================

    public function getNombreProduitsAttribute(): int
    {
        return $this->produits()->count();
    }

    public function getProduitsActifsAttribute()
    {
        return $this->produits()->where('actif', true)->count();
    }
}