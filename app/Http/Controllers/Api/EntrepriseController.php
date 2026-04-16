<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Versement;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    protected function entreprise(Request $request)
    {
        return $request->user()->entreprise;
    }

    public function dashboard(Request $request)
    {
        $e = $this->entreprise($request);

        return response()->json([
            'entreprise' => $e,
            'stats' => [
                'produits_actifs' => $e->produits()->where('est_actif', true)->count(),
                'commandes_en_cours' => $e->commandes()->whereIn('statut', ['en_attente', 'confirmee', 'en_preparation'])->count(),
                'commandes_ce_mois' => $e->commandes()->whereMonth('created_at', now()->month)->count(),
                'ca_ce_mois' => $e->commandes()
                    ->whereMonth('created_at', now()->month)
                    ->where('statut', 'livree')
                    ->sum('montant_produits'),
                'solde' => $e->portefeuille?->solde ?? 0,
            ],
        ]);
    }

    // -- Produits --

    public function produits(Request $request)
    {
        $produits = $this->entreprise($request)
            ->produits()
            ->with('categorie')
            ->latest()
            ->paginate(20);

        return response()->json($produits);
    }

    public function creerProduit(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'categorie_id' => 'required|exists:categories,id',
            'prix' => 'required|numeric|min:100',
            'prix_promo' => 'nullable|numeric|min:0|lt:prix',
            'stock' => 'nullable|integer|min:0',
            'poids_kg' => 'nullable|numeric|min:0',
            'est_fragile' => 'nullable|boolean',
        ]);

        $data['entreprise_id'] = $this->entreprise($request)->id;
        $data['est_actif'] = true;
        $produit = Produit::create($data);

        return response()->json(['message' => 'Produit cree.', 'produit' => $produit], 201);
    }

    public function modifierProduit(Request $request, Produit $produit)
    {
        if ($produit->entreprise_id !== $this->entreprise($request)->id) {
            return response()->json(['message' => 'Non autorise.'], 403);
        }

        $data = $request->validate([
            'nom' => 'sometimes|string|max:200',
            'description' => 'nullable|string|max:2000',
            'categorie_id' => 'sometimes|exists:categories,id',
            'prix' => 'sometimes|numeric|min:100',
            'prix_promo' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'poids_kg' => 'nullable|numeric|min:0',
            'est_fragile' => 'nullable|boolean',
            'est_actif' => 'nullable|boolean',
        ]);

        $produit->update($data);

        return response()->json(['message' => 'Produit modifie.', 'produit' => $produit]);
    }

    public function supprimerProduit(Request $request, Produit $produit)
    {
        if ($produit->entreprise_id !== $this->entreprise($request)->id) {
            return response()->json(['message' => 'Non autorise.'], 403);
        }

        $produit->update(['est_actif' => false]);

        return response()->json(['message' => 'Produit supprime.']);
    }

    // -- Commandes --

    public function commandes(Request $request)
    {
        $commandes = Commande::whereHas('commandeProduits', fn ($q) =>
            $q->where('entreprise_id', $this->entreprise($request)->id)
        )->with('user', 'produits')->latest()->paginate(15);

        return response()->json($commandes);
    }

    public function commandeDetail(Request $request, Commande $commande)
    {
        $commande->load(['user', 'commandeProduits.produit', 'livraison']);

        return response()->json($commande);
    }

    public function confirmerCommande(Request $request, Commande $commande)
    {
        if ($commande->statut !== 'en_attente') {
            return response()->json(['message' => 'Action impossible.'], 422);
        }

        $commande->update(['statut' => 'confirmee']);

        return response()->json(['message' => 'Commande confirmee.']);
    }

    public function marquerPrete(Request $request, Commande $commande)
    {
        if ($commande->statut !== 'confirmee') {
            return response()->json(['message' => 'Action impossible.'], 422);
        }

        $commande->update(['statut' => 'en_preparation']);

        return response()->json(['message' => 'Commande prete pour la livraison.']);
    }

    // -- Finances --

    public function finances(Request $request)
    {
        $e = $this->entreprise($request);

        return response()->json([
            'solde' => $e->portefeuille?->solde ?? 0,
            'total_gagne' => $e->portefeuille?->total_gagne ?? 0,
            'total_verse' => $e->portefeuille?->total_verse ?? 0,
            'versements' => $e->versements()->latest()->take(20)->get(),
            'abonnement' => $e->abonnement_actif,
        ]);
    }

    public function demanderVersement(Request $request)
    {
        $data = $request->validate([
            'montant' => 'required|numeric|min:' . config('bmje.versement_min', 5000),
            'mode' => 'required|in:orange_money,mtn_momo,wave,virement',
            'numero_compte' => 'required|string|max:50',
        ]);

        $e = $this->entreprise($request);
        $solde = $e->portefeuille?->solde ?? 0;

        if ($data['montant'] > $solde) {
            return response()->json(['message' => 'Solde insuffisant.'], 422);
        }

        $versement = Versement::create([
            'entreprise_id' => $e->id,
            'montant' => $data['montant'],
            'mode' => $data['mode'],
            'numero_compte' => $data['numero_compte'],
            'statut' => 'en_attente',
        ]);

        return response()->json(['message' => 'Demande de versement soumise.', 'versement' => $versement], 201);
    }
}
