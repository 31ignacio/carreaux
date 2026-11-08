<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    protected $fillable = ['designation', 'categorie', 'montant', 'date_depense', 'mode_paiement', 'beneficiaire', 'observations', 'justificatif', 'user_id', 'annulee_par', 'annulee_at', 'motif_annulation'];

    protected $casts = ['montant' => 'decimal:2', 'date_depense' => 'date', 'annulee_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function annuleur() { return $this->belongsTo(User::class, 'annulee_par'); }
    public function historiques() { return $this->hasMany(DepenseHistorique::class); }
}
