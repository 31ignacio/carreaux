<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'role',
        'actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'actif'             => 'boolean',
    ];

    // ==================== RELATIONS ====================

    public function factures()
    {
        return $this->hasMany(Facture::class)->where('nature', 'vente');
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }

    public function inventaires()
    {
        return $this->hasMany(Inventaire::class);
    }

    public function alertesTraitees()
    {
        return $this->hasMany(Alerte::class, 'traitee_par');
    }

    // ==================== HELPERS ====================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCaissier(): bool
    {
        return $this->role === 'caissier';
    }

    public function isCaissierHaut(): bool
    {
        return $this->role === 'caissierHaut';
    }

    public function isSuperviseur(): bool
    {
        return $this->role === 'superviseur';
    }

    public function isSecretaire(): bool
    {
        return $this->role === 'secretaire';
    }

    public function isMagasinier(): bool
    {
        return $this->role === 'magasinier';
    }

    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'       => 'Administrateur',
            'caissier'    => 'Caissier',
            'caissierHaut'=> 'Caissier Haut',
            'superviseur' => 'Superviseur',
            'secretaire'  => 'Secrétaire',
            'magasinier'  => 'Magasinier',
            default       => 'Inconnu',
        };
    }

    public function getRoleBadgeAttribute(): string
    {
        return match($this->role) {
            'admin'       => 'badge-danger',
            'caissier'    => 'badge-success',
            'caissierHaut'=> 'badge-warning',
            'superviseur' => 'badge-info',
            default       => 'badge-secondary',
        };
    }

    public function getAvatarClassAttribute(): string
    {
        return match($this->role) {
            'admin'       => 'ua-admin',
            'caissier'    => 'ua-caiss',
            'caissierHaut'=> 'ua-caiss-haut',
            'superviseur' => 'ua-sup',
            default       => 'ua-other',
        };
    }

    public function getRoleClassAttribute(): string
    {
        return match($this->role) {
            'admin'       => 'rp-admin',
            'caissier'    => 'rp-caiss',
            'caissierHaut'=> 'rp-caiss-haut',
            'superviseur' => 'rp-sup',
            default       => 'rp-other',
        };
    }

    public function getInitialsAttribute(): string
    {
        $parts = explode(' ', $this->nom_complet);
        $initials = '';
        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }
        return substr($initials, 0, 2);
    }

    // ==================== SCOPES ====================

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeInactifs($query)
    {
        return $query->where('actif', false);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeCaissiers($query)
    {
        return $query->where('role', 'caissier');
    }

    public function scopeCaissiersHaut($query)
    {
        return $query->where('role', 'caissierHaut');
    }

    public function scopeSuperviseurs($query)
    {
        return $query->where('role', 'superviseur');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
