<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom', 'prenom', 'email', 'telephone',
        'password', 'type', 'avatar', 'statut',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'derniere_connexion' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Relations ─────────────────────────────────────

    public function entreprise()
    {
        return $this->hasOne(Entreprise::class);
    }

    public function livreur()
    {
        return $this->hasOne(Livreur::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'client_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // ── Helpers ───────────────────────────────────────

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function estAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function estEntreprise(): bool
    {
        return $this->type === 'entreprise';
    }

    public function estClient(): bool
    {
        return $this->type === 'client';
    }

    public function estLivreur(): bool
    {
        return $this->type === 'livreur';
    }
}
