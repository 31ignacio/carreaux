<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;

    protected $table = 'mouvements_stock';

    protected $fillable = [
        'produit_id',
        'user_id',
        'fournisseur_id',
        'type',
        'quantite',
        'prix_unitaire',
        'stock_avant',
        'stock_apres',
        'motif',
        'reference_doc',
    ];

    protected $casts = [
        'quantite'      => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'stock_avant'   => 'decimal:2',
        'stock_apres'   => 'decimal:2',
    ];

    // ==================== RELATIONS ====================

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    // Dans app/Models/MouvementStock.php
    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    // ==================== HELPERS ====================

    public function getMontantTotalAttribute(): float
    {
        return (float) $this->quantite * (float) $this->prix_unitaire;
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'entree'      => 'Entrée',
            'sortie'      => 'Sortie',
            'ajustement'  => 'Ajustement',
            'retour'      => 'Retour',
            'perte'       => 'Perte',
            default       => 'Inconnu',
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'entree'     => 'badge-success',
            'sortie'     => 'badge-danger',
            'ajustement' => 'badge-info',
            'retour'     => 'badge-warning',
            'perte'      => 'badge-dark',
            default      => 'badge-secondary',
        };
    }
}