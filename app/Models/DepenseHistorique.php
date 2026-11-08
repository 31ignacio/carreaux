<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepenseHistorique extends Model
{
    protected $table = 'depense_historiques';
    protected $fillable = ['depense_id', 'user_id', 'avant', 'apres'];
    protected $casts = ['avant' => 'array', 'apres' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
}
