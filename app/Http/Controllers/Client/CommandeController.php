<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Produit;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::with(['entreprise', 'livraison'])
            ->where('client_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('client.commandes', compact('commandes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'produits' => 'required|array|min:1',
            'produits.*.id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'adresse_livraison' => 'required|string',
            'ville_livraison' => 'required|string',
            'telephone_livraison' => 'required|string',
            'mode_paiement' => 'required|in:orange_money,mtn_momo,wave,especes,virement',
            'notes_client' => 'nullable|string',
        ]);

        // Charger les produits et verifier qu'ils viennent de la meme entreprise
        $premier_produit = Produit::findOrFail($data['produits'][0]['id']);
        $entreprise = $premier_produit->entreprise;

        $montant_produits = 0;
        $lignes = [];

        foreach ($data['produits'] as $item) {
            $produit = Produit::findOrFail($item['id']);

            if ($produit->entreprise_id !== $entreprise->id) {
                return back()->withErrors(['produits' => 'Tous les produits doivent venir de la meme entreprise.']);
            }

            $prix = $produit->prix_actuel;
            $montant_ligne = $prix * $item['quantite'];
            $montant_produits += $montant_ligne;

            $lignes[] = [
                'produit_id' => $produit->id,
                'quantite' => $item['quantite'],
                'prix_unitaire' => $prix,
                'montant' => $montant_ligne,
            ];
        }

        // Calcul des frais
        $frais_livraison = 1500; // forfait fixe pour l'instant
        $commission = (int) ($montant_produits * $entreprise->commission_taux / 100);
        $montant_total = $montant_produits + $frais_livraison;
        $montant_entreprise = $montant_produits - $commission;

        $commande = Commande::create([
            'client_id' => auth()->id(),
            'entreprise_id' => $entreprise->id,
            'montant_produits' => $montant_produits,
            'frais_livraison' => $frais_livraison,
            'montant_total' => $montant_total,
            'commission_bmje' => $commission + $frais_livraison,
            'montant_entreprise' => $montant_entreprise,
            'adresse_livraison' => $data['adresse_livraison'],
            'ville_livraison' => $data['ville_livraison'],
            'telephone_livraison' => $data['telephone_livraison'],
            'mode_paiement' => $data['mode_paiement'],
            'notes_client' => $data['notes_client'] ?? null,
        ]);

        foreach ($lignes as $ligne) {
            CommandeProduit::create([...$ligne, 'commande_id' => $commande->id]);
        }

        // Enregistrer la transaction client
        Transaction::create([
            'commande_id' => $commande->id,
            'type' => 'paiement_client',
            'montant' => $montant_total,
            'de_user_id' => auth()->id(),
            'mode' => $data['mode_paiement'],
            'statut' => $data['mode_paiement'] === 'especes' ? 'en_attente' : 'en_attente',
            'date_transaction' => now(),
        ]);

        return redirect()->route('client.commandes.show', $commande)
            ->with('success', 'Commande passee avec succes !');
    }

    public function show(Commande $commande)
    {
        if ($commande->client_id !== auth()->id()) {
            abort(403);
        }

        $commande->load([
            'entreprise', 'commandeProduits.produit',
            'livraison.livreur.user', 'livraison.suivis',
        ]);

        return view('client.commandes-show', compact('commande'));
    }

    public function annuler(Commande $commande)
    {
        if ($commande->client_id !== auth()->id()) {
            abort(403);
        }

        if (!$commande->peutEtreAnnulee()) {
            return back()->with('error', 'Cette commande ne peut plus etre annulee.');
        }

        $commande->update(['statut' => 'annulee']);

        return back()->with('success', 'Commande annulee.');
    }
}
