<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'facture_id',
        'produit_id',
        'libelle',
        'unite',
        'quantite',
        'prix_unitaire',
        'sous_total',
    ];

    protected $casts = [
        'quantite'      => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'sous_total'    => 'decimal:2',
    ];

    // ==================== RELATIONS ====================

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function livraisons()
    {
        return $this->hasMany(LivraisonCommandeLigne::class, 'bon_commande_ligne_id');
    }

    public function getQuantiteCommandeeAttribute(): float
    {
        return (float) $this->quantite;
    }

    public function getQuantiteLivreeAttribute(): float
    {
        return (float) $this->livraisons()->sum('quantite');
    }

    public function getQuantiteRestanteAttribute(): float
    {
        return max($this->quantite_commandee - $this->quantite_livree, 0);
    }
}
