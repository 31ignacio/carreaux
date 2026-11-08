<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementAvance extends Model
{
    protected $table = 'mouvements_avances';

    protected $fillable = ['reference', 'client_id', 'facture_id', 'user_id', 'type', 'montant', 'mode_paiement', 'notes'];

    protected $casts = ['montant' => 'decimal:2'];

    public function client() { return $this->belongsTo(Client::class); }
    public function facture() { return $this->belongsTo(Facture::class); }
    public function user() { return $this->belongsTo(User::class); }
}
