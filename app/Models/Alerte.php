<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'type',
        'stock_au_moment',
        'traitee',
        'traitee_par',
        'traitee_le',
    ];

    protected $casts = [
        'traitee'         => 'boolean',
        'stock_au_moment' => 'decimal:2',
        'traitee_le'      => 'datetime',
    ];

    // ==================== RELATIONS ====================

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function traitePar()
    {
        return $this->belongsTo(User::class, 'traitee_par');
    }

    // ==================== HELPERS ====================

    public function traiter(int $userId): void
    {
        $this->update([
            'traitee'     => true,
            'traitee_par' => $userId,
            'traitee_le'  => now(),
        ]);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'rupture'        => 'Rupture de stock',
            'stock_faible'   => 'Stock faible',
            'stock_negatif'  => 'Stock négatif',
            default          => 'Inconnu',
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'rupture'       => 'badge-danger',
            'stock_faible'  => 'badge-warning',
            'stock_negatif' => 'badge-dark',
            default         => 'badge-secondary',
        };
    }
}