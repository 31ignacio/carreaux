<?php
// app/Models/Produit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie_id',
        'fournisseur_id',
        'libelle',
        'reference',
        'code_barre',
        'unite',
        'prix_achat',
        'prix_detail',
        'seuil_detail',   // ← nouveau : qté MAX pour prix détail
        'prix_moyen',
        'seuil_moyen',    // ← nouveau : qté MAX pour prix moyen
        'prix_gros',
        'seuil_gros',     // ← nouveau : info complémentaire (au-delà de seuil_moyen)
        'stock_actuel',
        'stock_minimum',
        'stock_maximum',
        'actif',
        'description',
        'image',
    ];

    protected $casts = [
        'prix_detail'   => 'decimal:2',
        'prix_moyen'    => 'decimal:2',
        'prix_gros'     => 'decimal:2',
        'prix_achat'    => 'decimal:2',
        'stock_actuel'  => 'decimal:2',
        'stock_minimum' => 'decimal:2',
        'stock_maximum' => 'decimal:2',
        'seuil_detail'  => 'integer',
        'seuil_moyen'   => 'integer',
        'seuil_gros'    => 'integer',
        'actif'         => 'boolean',
    ];

    // ==================== RELATIONS ====================

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }

    public function factureLignes()
    {
        return $this->hasMany(FactureLigne::class);
    }

    public function inventaireLignes()
    {
        return $this->hasMany(InventaireLigne::class);
    }

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }

    // ==================== PRIX PAR QUANTITÉ ====================

    /**
     * Retourne le prix unitaire applicable selon la quantité commandée.
     *
     * Si un prix explicite est fourni depuis la requête, il est prioritaire.
     * Sinon, on applique la logique par paliers du produit.
     */
    public function getPrixParQuantite(float $quantite, ?float $prixUnitaire = null): float
    {
        if ($prixUnitaire !== null) {
            return (float) $prixUnitaire;
        }

        $prixDetail = (float) $this->prix_detail;
        $prixMoyen  = $this->prix_moyen  ? (float) $this->prix_moyen  : null;
        $prixGros   = $this->prix_gros   ? (float) $this->prix_gros   : null;

        $seuilDetail = (int) ($this->seuil_detail ?? 1);
        $seuilMoyen  = $this->seuil_moyen ? (int) $this->seuil_moyen : null;

        // Palier détail : qté <= seuil_detail
        if ($quantite <= $seuilDetail) {
            return $prixDetail;
        }

        // Palier moyen : seuil_detail < qté <= seuil_moyen
        if ($seuilMoyen !== null && $quantite <= $seuilMoyen) {
            return $prixMoyen ?? $prixDetail;
        }

        // Palier gros : qté > seuil_moyen
        if ($seuilMoyen !== null && $quantite > $seuilMoyen) {
            return $prixGros ?? $prixDetail;
        }

        // Fallback sécurité
        return $prixDetail;
    }

    /**
     * Retourne un tableau décrivant les paliers de prix pour la caisse (JS).
     * Utilisé pour passer les infos au front-end.
     */
    public function getPaliersAttribute(): array
    {
        return [
            'detail' => [
                'prix'   => (float) $this->prix_detail,
                'seuil'  => (int) ($this->seuil_detail ?? 1),
                'label'  => 'Détail (≤ ' . ($this->seuil_detail ?? 1) . ' ' . $this->unite . ')',
            ],
            'moyen'  => $this->prix_moyen && $this->seuil_moyen ? [
                'prix'   => (float) $this->prix_moyen,
                'seuil'  => (int) $this->seuil_moyen,
                'label'  => 'Moyen (≤ ' . $this->seuil_moyen . ' ' . $this->unite . ')',
            ] : null,
            'gros'   => $this->prix_gros && $this->seuil_moyen ? [
                'prix'   => (float) $this->prix_gros,
                'seuil'  => null,
                'label'  => 'Gros (> ' . $this->seuil_moyen . ' ' . $this->unite . ')',
            ] : null,
        ];
    }

    // ==================== STOCK ====================

    public function isStockFaible(): bool
    {
        return $this->stock_actuel <= $this->stock_minimum && $this->stock_actuel > 0;
    }

    public function isRupture(): bool
    {
        return $this->stock_actuel <= 0;
    }

    public function getStatutStockAttribute(): string
    {
        if ($this->isRupture())     return 'rupture';
        if ($this->isStockFaible()) return 'faible';
        return 'normal';
    }

    public function getStatutStockBadgeAttribute(): string
    {
        return match($this->statut_stock) {
            'rupture' => 'badge-danger',
            'faible'  => 'badge-warning',
            default   => 'badge-success',
        };
    }

    public function getStatutStockLabelAttribute(): string
    {
        return match($this->statut_stock) {
            'rupture' => 'Rupture',
            'faible'  => 'Stock faible',
            default   => 'Normal',
        };
    }

    // ==================== STOCK OPERATIONS ====================

    public function entreeStock(
        float $quantite,
        float $prixUnitaire,
        int $userId,
        ?int $fournisseurId = null,
        ?string $motif = null,
        ?string $referenceDoc = null
        ): MouvementStock {
        $stockAvant = (float) $this->stock_actuel;
        $this->stock_actuel = $stockAvant + $quantite;
        $this->save();

        $mouvement = MouvementStock::create([
            'produit_id'     => $this->id,
            'user_id'        => $userId,
            'fournisseur_id' => $fournisseurId,
            'type'           => 'entree',
            'quantite'       => $quantite,
            'prix_unitaire'  => $prixUnitaire,
            'stock_avant'    => $stockAvant,
            'stock_apres'    => $this->stock_actuel,
            'motif'          => $motif,
            'reference_doc'  => $referenceDoc,
        ]);

        $this->verifierAlertes();
        return $mouvement;
    }

    public function sortieStock(
            float $quantite,
            float $prixUnitaire,
            int $userId,
            ?string $motif = null,
            ?int $factureId = null,
            string $type = 'sortie'
        ): MouvementStock {
            $stockAvant = (float) $this->stock_actuel;
            $this->stock_actuel = max($stockAvant - $quantite, 0);
            $this->save();

            $mouvement = MouvementStock::create([
                'produit_id'    => $this->id,
                'user_id'       => $userId,
                'facture_id'    => $factureId,
                'type'          => $type,
                'quantite'      => $quantite,
                'prix_unitaire' => $prixUnitaire,
                'stock_avant'   => $stockAvant,
                'stock_apres'   => $this->stock_actuel,
                'motif'         => $motif,
            ]);

            $this->verifierAlertes();
            return $mouvement;
    }
    public function verifierAlertes(): void
    {
        if ($this->isRupture()) {
            Alerte::firstOrCreate(
                ['produit_id' => $this->id, 'type' => 'rupture', 'traitee' => false],
                ['stock_au_moment' => $this->stock_actuel]
            );
        } elseif ($this->isStockFaible()) {
            Alerte::firstOrCreate(
                ['produit_id' => $this->id, 'type' => 'stock_faible', 'traitee' => false],
                ['stock_au_moment' => $this->stock_actuel]
            );
        }
    }

}
