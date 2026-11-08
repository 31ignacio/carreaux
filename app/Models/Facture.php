<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numero',
        'operation_reference',
        'client_nom','client_id',
        'client_telephone',
        'statut',
        'mode_paiement',
        'sous_total',
        'remise',
        'total',
        'montant_recu',
        'montant_avance',
        'monnaie',
        'notes','reste_a_payer',
        'nature',
        'livraison_statut',
    ];

    protected $casts = [
        'sous_total'    => 'decimal:2',
        'remise'        => 'decimal:2',
        'total'         => 'decimal:2',
        'montant_recu'  => 'decimal:2',
        'montant_avance' => 'decimal:2',
        'monnaie'       => 'decimal:2',
    ];

    // ==================== RELATIONS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lignes()
    {
        return $this->hasMany(FactureLigne::class);
    }

    public function livraisons()
    {
        return $this->hasMany(LivraisonCommande::class, 'bon_commande_id');
    }

    public function scopeVentes($query)
    {
        return $query->where('nature', 'vente');
    }

    public function scopeCommandes($query)
    {
        return $query->where('nature', 'commande');
    }

    public static function genererReferenceCommande(): string
    {
        do {
            $reference = 'BC-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (self::where('numero', $reference)->exists());

        return $reference;
    }

    public function getReferenceAttribute(): string
    {
        return $this->numero;
    }

    public function getStatutLivraisonLabelAttribute(): string
    {
        return match ($this->livraison_statut) {
            'partielle' => 'Partiellement livrée',
            'livree' => 'Entièrement livrée',
            default => 'En attente de livraison',
        };
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class);
    }

    // ==================== HELPERS ====================

    public static function genererNumero(): string
    {
        $prefix  = 'BL-' . date('Y') . '-';
        $dernier = self::where('numero', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $numero = $dernier
            ? (int) substr($dernier->numero, strlen($prefix)) + 1
            : 1;

        return $prefix . str_pad($numero, 5, '0', STR_PAD_LEFT);
    }

    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'en_cours' => 'badge-warning',
            'payee'    => 'badge-success',
            'annulee'  => 'badge-danger',
            default    => 'badge-secondary',
        };
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'en_cours' => 'En cours',
            'payee'    => 'Payée',
            'annulee'  => 'Annulée',
            default    => 'Inconnu',
        };
    }

    public function getModePaiementLabelAttribute(): string
    {
        if ((float) $this->montant_avance > 0) {
            return $this->mode_paiement === 'credit' ? 'Avance + crédit' : 'Avance client';
        }

        return match($this->mode_paiement) {
            'espece'       => 'Espèce',
            'carte'        => 'Carte bancaire',
            'mobile_money' => 'Mobile Money',
            'credit'       => 'Crédit',
            default        => 'Inconnu',
        };
    }

    public function getTypePrixLabelAttribute(): string
    {
        return match($this->type_prix) {
            'detail' => 'Détail',
            'moyen'  => 'Moyen',
            'gros'   => 'Gros',
            default  => 'Inconnu',
        };
    }

    // app/Models/Facture.php — ajouter ces méthodes

    public function reglements()
    {
        return $this->hasMany(Reglement::class);
    }

    public function mouvementsAvance()
    {
        return $this->hasMany(MouvementAvance::class);
    }
    public function client()
{
    return $this->belongsTo(Client::class);
}

    // ✅ Toujours requête fraîche BDD
    public function getMontantPayeAttribute()
    {
        return (float) $this->reglements()->sum('montant') + (float) $this->montant_avance;
    }

    public function getResteAPayerAttribute()
    {
        return max($this->total - $this->reglements()->sum('montant') - (float) $this->montant_avance, 0);
    }

    public function getPourcentagePayeAttribute()
    {
        if ($this->total <= 0) return 100;
        $pct = ($this->montant_paye / $this->total) * 100;
        return min(round($pct), 100);
    }

    public function isEntierementRegle()
    {
        return $this->getResteAPayerAttribute() <= 0;
    }
}
