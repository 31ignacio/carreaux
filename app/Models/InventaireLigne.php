<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventaireLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventaire_id',
        'produit_id',
        'stock_theorique',
        'stock_physique',
        'ecart',
        'ecart_valeur',
    ];

    protected $casts = [
        'stock_theorique' => 'decimal:2',
        'stock_physique'  => 'decimal:2',
        'ecart'           => 'decimal:2',
        'ecart_valeur'    => 'decimal:2',
    ];

    // ==================== RELATIONS ====================

    public function inventaire()
    {
        return $this->belongsTo(Inventaire::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // ==================== HELPERS ====================

    public function calculerEcart(): void
    {
        if ($this->stock_physique !== null) {
            $this->ecart       = (float) $this->stock_physique - (float) $this->stock_theorique;
            $this->ecart_valeur = $this->ecart * (float) $this->produit->prix_detail;
            $this->save();
        }
    }
}