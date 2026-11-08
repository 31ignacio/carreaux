<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'prenom', 'type',
        'telephone', 'email',
        'adresse', 'ifu',
        'solde_credit', 'solde_avance', 'actif',
    ];

    protected $casts = [
        'solde_credit' => 'decimal:2',
        'solde_avance' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function factures()
    {
        return $this->hasMany(Facture::class, 'client_id')->where('nature', 'vente');
    }

    public function remboursements()
    {
        return $this->hasMany(RemboursementClient::class);
    }

    public function mouvementsAvance()
    {
        return $this->hasMany(MouvementAvance::class);
    }

    public function getNomCompletAttribute(): string
    {
        return $this->prenom
            ? $this->prenom . ' ' . $this->nom
            : $this->nom;
    }

    public function getInitialesAttribute(): string
    {
        $parts = array_filter([$this->prenom, $this->nom]);

        return strtoupper(implode('', array_map(fn ($p) => $p[0], $parts)));
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'entreprise' => 'Entreprise',
            default => 'Particulier',
        };
    }

    public function getTypeBadgeColorAttribute(): array
    {
        return match ($this->type) {
            'entreprise' => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
            default => ['bg' => '#f0fdf4', 'color' => '#15803d'],
        };
    }

    public function getTotalDepenseAttribute(): float
    {
        return (float) $this->factures()->where('statut', 'payee')->sum('total');
    }

    public function getNbFacturesAttribute(): int
    {
        return $this->factures()->count();
    }

    public function getCreancesAttribute(): float
    {
        return (float) $this->factures()
            ->where('statut', 'en_cours')
            ->where('mode_paiement', 'credit')
            ->sum('reste_a_payer');
    }
}
