<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'numero_cni', 'permis_conduire',
        'type_vehicule', 'immatriculation', 'zone_activite',
        'disponible', 'en_course', 'latitude', 'longitude',
        'salaire_mensuel', 'prime_par_course', 'statut',
    ];

    protected function casts(): array
    {
        return [
            'disponible' => 'boolean',
            'en_course' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livraisons()
    {
        return $this->hasMany(Livraison::class);
    }

    // ── Helpers ───────────────────────────────────────

    public function estDisponible(): bool
    {
        return $this->disponible && !$this->en_course && $this->statut === 'actif';
    }

    public function getNomCompletAttribute(): string
    {
        return $this->user->nom_complet;
    }

    /**
     * Trouver le livreur disponible le plus proche d'une position.
     */
    public static function plusProche(float $lat, float $lng, string $zone = null)
    {
        $query = static::where('disponible', true)
                       ->where('en_course', false)
                       ->where('statut', 'actif')
                       ->whereNotNull('latitude')
                       ->whereNotNull('longitude');

        if ($zone) {
            $query->where('zone_activite', $zone);
        }

        return $query->selectRaw("*, (
            6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(latitude))
            )
        ) AS distance_km", [$lat, $lng, $lat])
        ->orderBy('distance_km')
        ->first();
    }
}
