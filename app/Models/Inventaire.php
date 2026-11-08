<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'categorie_filtre',
        'statut',
        'total_ecart_valeur',
        'notes',
        'date_cloture',
    ];

    protected $casts = [
        'total_ecart_valeur' => 'decimal:2',
        'date_cloture'       => 'datetime',
    ];

    // ==================== RELATIONS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lignes()
    {
        return $this->hasMany(InventaireLigne::class);
    }

    // ==================== HELPERS ====================

    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'en_cours' => 'badge-warning',
            'termine'  => 'badge-success',
            'annule'   => 'badge-danger',
            default    => 'badge-secondary',
        };
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'en_cours' => 'En cours',
            'termine'  => 'Terminé',
            'annule'   => 'Annulé',
            default    => 'Inconnu',
        };
    }

    public function getNombreEcartsAttribute(): int
    {
        return $this->lignes()->where('ecart', '!=', 0)->count();
    }
}