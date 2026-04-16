<?php

namespace App\Services;

use App\Models\Produit;
use Illuminate\Support\Facades\Session;

class PanierService
{
    protected string $key = 'panier';

    /**
     * Obtenir le contenu du panier
     */
    public function contenu(): array
    {
        return Session::get($this->key, []);
    }

    /**
     * Ajouter un produit au panier
     */
    public function ajouter(Produit $produit, int $quantite = 1): void
    {
        $panier = $this->contenu();
        $id = $produit->id;

        if (isset($panier[$id])) {
            $panier[$id]['quantite'] += $quantite;
        } else {
            $panier[$id] = [
                'produit_id' => $produit->id,
                'nom' => $produit->nom,
                'prix' => $produit->prix_actuel,
                'quantite' => $quantite,
                'entreprise_id' => $produit->entreprise_id,
                'entreprise_nom' => $produit->entreprise->raison_sociale,
                'est_fragile' => $produit->est_fragile,
            ];
        }

        Session::put($this->key, $panier);
    }

    /**
     * Modifier la quantite
     */
    public function modifierQuantite(int $produit_id, int $quantite): void
    {
        $panier = $this->contenu();

        if (isset($panier[$produit_id])) {
            if ($quantite <= 0) {
                unset($panier[$produit_id]);
            } else {
                $panier[$produit_id]['quantite'] = $quantite;
            }
        }

        Session::put($this->key, $panier);
    }

    /**
     * Supprimer un produit
     */
    public function supprimer(int $produit_id): void
    {
        $panier = $this->contenu();
        unset($panier[$produit_id]);
        Session::put($this->key, $panier);
    }

    /**
     * Vider le panier
     */
    public function vider(): void
    {
        Session::forget($this->key);
    }

    /**
     * Nombre d'articles
     */
    public function nombreArticles(): int
    {
        return array_sum(array_column($this->contenu(), 'quantite'));
    }

    /**
     * Total du panier
     */
    public function total(): int
    {
        $total = 0;
        foreach ($this->contenu() as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        return $total;
    }

    /**
     * Verifier si le panier contient des produits fragiles
     */
    public function contientFragile(): bool
    {
        foreach ($this->contenu() as $item) {
            if ($item['est_fragile'] ?? false) return true;
        }
        return false;
    }

    /**
     * Grouper par entreprise
     */
    public function parEntreprise(): array
    {
        $groupes = [];
        foreach ($this->contenu() as $item) {
            $eid = $item['entreprise_id'];
            if (!isset($groupes[$eid])) {
                $groupes[$eid] = [
                    'entreprise_nom' => $item['entreprise_nom'],
                    'items' => [],
                    'sous_total' => 0,
                ];
            }
            $groupes[$eid]['items'][] = $item;
            $groupes[$eid]['sous_total'] += $item['prix'] * $item['quantite'];
        }
        return $groupes;
    }
}
