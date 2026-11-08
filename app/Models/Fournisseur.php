<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'adresse',
        'ville',
        'contact_personne',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // ==================== RELATIONS ====================

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }

    // ==================== HELPERS ====================

    public function getNombreProduitsAttribute(): int
    {
        return $this->produits()->count();
    }

    public function getTotalAchatsAttribute()
    {
        return $this->mouvementsStock()
            ->where('type', 'entree')
            ->sum(\DB::raw('quantite * prix_unitaire'));
    }
}