<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemboursementClient extends Model
{
    protected $table = 'remboursements_clients';

    protected $fillable = ['reference', 'client_id', 'user_id', 'montant', 'mode_paiement', 'notes'];

    protected $casts = ['montant' => 'decimal:2'];

    public function client() { return $this->belongsTo(Client::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function getModePaiementLabelAttribute(): string
    {
        return match ($this->mode_paiement) {
            'espece' => 'Espèces', 'mobile_money' => 'Mobile Money', 'carte' => 'Carte bancaire', default => $this->mode_paiement,
        };
    }
}
