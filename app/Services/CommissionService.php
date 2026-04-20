<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\PortefeuilleEntreprise;

class CommissionService
{
    /**
     * Pour compatibilité avec les tests
     */
    public function calculer(float $montant, float $taux = 10): array
    {
        $commission = round($montant * $taux / 100);
        return [
            'commission' => $commission,
            'montant_entreprise' => $montant - $commission,
        ];
    }

    /**
     * Calculer la commission BMJE sur une commande
     */
    public function calculerCommission(Commande $commande): array
    {
        $entreprise = $commande->entreprise;
        $taux = $entreprise->commission_taux ?? config('bmje.commission_defaut');

        $montant_produits = $commande->montant_produits;
        $commission = round($montant_produits * $taux / 100);
        $part_entreprise = $montant_produits - $commission;

        return [
            'montant_produits' => $montant_produits,
            'taux_commission' => $taux,
            'commission_bmje' => $commission,
            'part_entreprise' => $part_entreprise,
            'frais_livraison' => $commande->frais_livraison,
            'total_client' => $montant_produits + $commande->frais_livraison,
        ];
    }

    /**
     * Crediter le portefeuille de l'entreprise apres paiement confirme
     */
    public function crediterEntreprise(Commande $commande): void
    {
        $repartition = $this->calculerCommission($commande);

        $commande->update([
            'commission_bmje' => $repartition['commission_bmje'],
            'montant_entreprise' => $repartition['part_entreprise'],
        ]);

        $portefeuille = PortefeuilleEntreprise::firstOrCreate(
            ['entreprise_id' => $commande->entreprise_id],
            ['solde_disponible' => 0, 'solde_en_attente' => 0, 'total_gagne' => 0]
        );

        $portefeuille->increment('solde_disponible', $repartition['part_entreprise']);
        $portefeuille->increment('total_gagne', $repartition['part_entreprise']);
    }
}
