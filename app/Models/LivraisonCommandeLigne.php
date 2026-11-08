<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LivraisonCommandeLigne extends Model
{
    protected $table = 'livraison_commande_lignes';
    protected $fillable = ['livraison_id', 'bon_commande_ligne_id', 'produit_id', 'libelle', 'unite', 'quantite'];
    protected $casts = ['quantite' => 'decimal:2'];

    public function livraison() { return $this->belongsTo(LivraisonCommande::class, 'livraison_id'); }
    public function factureLigne() { return $this->belongsTo(FactureLigne::class, 'bon_commande_ligne_id'); }
    public function produit() { return $this->belongsTo(Produit::class); }
}
