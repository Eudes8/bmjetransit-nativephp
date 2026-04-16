<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Livraison extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id', 'livreur_id', 'numero_tracking',
        'adresse_enlevement', 'adresse_livraison', 'distance_km',
        'statut', 'date_enlevement', 'date_livraison_estimee',
        'date_livraison_reelle', 'photo_preuve', 'signature',
        'nom_receveur', 'prime_livreur', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_enlevement' => 'datetime',
            'date_livraison_estimee' => 'datetime',
            'date_livraison_reelle' => 'datetime',
        ];
    }

    // ── Auto-tracking ─────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Livraison $livraison) {
            if (empty($livraison->numero_tracking)) {
                $livraison->numero_tracking = 'TRK-' . strtoupper(Str::random(8));
            }
        });
    }

    // ── Relations ─────────────────────────────────────

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function livreur()
    {
        return $this->belongsTo(Livreur::class);
    }

    public function suivis()
    {
        return $this->hasMany(SuiviLivraison::class)->orderBy('horodatage', 'desc');
    }

    // ── Helpers ───────────────────────────────────────

    public function estTerminee(): bool
    {
        return in_array($this->statut, ['livree', 'retour']);
    }

    public function ajouterSuivi(string $statut, string $description = null, float $lat = null, float $lng = null, int $userId = null): SuiviLivraison
    {
        return $this->suivis()->create([
            'statut' => $statut,
            'description' => $description,
            'latitude' => $lat,
            'longitude' => $lng,
            'horodatage' => now(),
            'created_by' => $userId,
        ]);
    }
}
