<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{
    protected $fillable = [
        'facture_id',
        'user_id',
        'montant',
        'mode_paiement',
        'notes',
    ];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getModePaiementLabelAttribute()
    {
        return match($this->mode_paiement) {
            'espece'       => '💵 Espèce',
            'mobile_money' => '📱 Mobile Money',
            'carte'        => '💳 Carte bancaire',
            default        => $this->mode_paiement,
        };
    }
}