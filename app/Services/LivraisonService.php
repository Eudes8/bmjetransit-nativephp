<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\Livreur;
use App\Models\Livraison;
use App\Models\SuiviLivraison;
use Illuminate\Support\Str;

class LivraisonService
{
    /**
     * Calculer les frais de livraison
     */
    public function calculerFrais(float $distance_km, bool $fragile = false): int
    {
        $config = config('bmje.livraison');
        $frais = $config['frais_base'] + ($distance_km * $config['frais_km']);

        if ($fragile) {
            $frais += $config['frais_fragile'];
        }

        return (int) round($frais);
    }

    /**
     * Creer une livraison et assigner un livreur
     */
    public function assignerLivreur(Commande $commande, Livreur $livreur): Livraison
    {
        $livraison = Livraison::create([
            'commande_id' => $commande->id,
            'livreur_id' => $livreur->id,
            'numero_tracking' => 'BT-' . strtoupper(Str::random(8)),
            'adresse_enlevement' => $commande->entreprise->adresse,
            'adresse_livraison' => $commande->adresse_livraison,
            'statut' => 'assignee',
        ]);

        $this->ajouterSuivi($livraison, 'assignee', 'Livreur assigne : ' . $livreur->user->nom_complet);

        $livreur->update(['disponible' => false]);

        return $livraison;
    }

    /**
     * Ajouter une etape de suivi
     */
    public function ajouterSuivi(Livraison $livraison, string $statut, string $description = null, float $lat = null, float $lng = null): SuiviLivraison
    {
        $suivi = SuiviLivraison::create([
            'livraison_id' => $livraison->id,
            'statut' => $statut,
            'description' => $description,
            'latitude' => $lat,
            'longitude' => $lng,
            'horodatage' => now(),
        ]);

        $livraison->update(['statut' => $statut]);

        if ($statut === 'en_route_enlevement') {
            $livraison->update(['heure_enlevement' => now()]);
        } elseif ($statut === 'livree') {
            $livraison->update(['heure_livraison' => now()]);
            $livraison->livreur->update(['disponible' => true]);
            $livraison->livreur->increment('nombre_courses');
            $livraison->commande->update(['statut' => 'livree']);
        }

        return $suivi;
    }

    /**
     * Trouver un livreur disponible dans une zone
     */
    public function trouverLivreurDisponible(string $zone): ?Livreur
    {
        return Livreur::where('disponible', true)
            ->where('statut', 'actif')
            ->where('zone_activite', $zone)
            ->orderBy('note_moyenne', 'desc')
            ->first();
    }

    /**
     * Etapes de suivi valides
     */
    public static function etapesSuivi(): array
    {
        return [
            'assignee' => 'Livreur assigne',
            'en_route_enlevement' => 'En route pour enlever',
            'colis_enleve' => 'Colis enleve',
            'en_livraison' => 'En cours de livraison',
            'livree' => 'Livree',
            'echouee' => 'Livraison echouee',
        ];
    }
}
