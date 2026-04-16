<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'entreprise_id', 'categorie_id', 'nom', 'description',
        'prix', 'prix_promo', 'en_promo', 'images', 'stock',
        'poids_kg', 'est_fragile', 'statut',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'en_promo' => 'boolean',
            'est_fragile' => 'boolean',
        ];
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function commandeProduits()
    {
        return $this->hasMany(CommandeProduit::class);
    }

    // ── Helpers ───────────────────────────────────────

    public function getPrixActuelAttribute(): int
    {
        return ($this->en_promo && $this->prix_promo) ? $this->prix_promo : $this->prix;
    }

    public function estDisponible(): bool
    {
        if ($this->statut !== 'actif') return false;
        if ($this->stock !== null && $this->stock <= 0) return false;
        return true;
    }

    public function getPrixFormatAttribute(): string
    {
        return number_format($this->prix_actuel, 0, ',', ' ') . ' XOF';
    }
}
