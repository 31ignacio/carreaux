<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LivraisonCommande extends Model
{
    protected $table = 'livraisons_commande';
    protected $fillable = ['bon_commande_id', 'user_id', 'complete', 'livree_le'];
    protected $casts = ['complete' => 'boolean', 'livree_le' => 'datetime'];

    public function facture() { return $this->belongsTo(Facture::class, 'bon_commande_id'); }
    public function user() { return $this->belongsTo(User::class); }
    public function lignes() { return $this->hasMany(LivraisonCommandeLigne::class, 'livraison_id'); }
}
