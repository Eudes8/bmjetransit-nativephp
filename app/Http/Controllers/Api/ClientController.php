<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Produit;
use App\Services\CommissionService;
use App\Services\LivraisonService;
use App\Services\PanierService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function __construct(
        protected PanierService $panier,
        protected LivraisonService $livraison,
        protected CommissionService $commission,
    ) {}

    // -- Panier --

    public function panier()
    {
        return response()->json([
            'items' => $this->panier->contenu(),
            'par_entreprise' => $this->panier->parEntreprise(),
            'total' => $this->panier->total(),
            'nombre' => $this->panier->nombreArticles(),
        ]);
    }

    public function ajouterPanier(Request $request, Produit $produit)
    {
        $request->validate(['quantite' => 'integer|min:1|max:99']);
        $this->panier->ajouter($produit, $request->input('quantite', 1));

        return response()->json([
            'message' => $produit->nom . ' ajoute au panier.',
            'nombre' => $this->panier->nombreArticles(),
        ]);
    }

    public function modifierPanier(Request $request, int $produit)
    {
        $request->validate(['quantite' => 'required|integer|min:0|max:99']);
        $this->panier->modifierQuantite($produit, $request->quantite);

        return response()->json(['message' => 'Panier mis a jour.', 'total' => $this->panier->total()]);
    }

    public function supprimerPanier(int $produit)
    {
        $this->panier->supprimer($produit);
        return response()->json(['message' => 'Produit retire.']);
    }

    public function viderPanier()
    {
        $this->panier->vider();
        return response()->json(['message' => 'Panier vide.']);
    }

    // -- Checkout --

    public function calculerFrais(Request $request)
    {
        $request->validate(['distance_km' => 'required|numeric|min:0']);
        $frais = $this->livraison->calculerFrais($request->distance_km, $this->panier->contientFragile());

        return response()->json([
            'sous_total' => $this->panier->total(),
            'frais_livraison' => $frais,
            'total' => $this->panier->total() + $frais,
        ]);
    }

    // -- Commandes --

    public function commander(Request $request)
    {
        $data = $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'adresse_livraison' => 'required|string|max:255',
            'ville_livraison' => 'required|string|max:100',
            'telephone_livraison' => 'required|string|max:20',
            'mode_paiement' => 'required|in:orange_money,mtn_momo,wave,especes',
            'notes_client' => 'nullable|string|max:500',
        ]);

        if ($this->panier->nombreArticles() === 0) {
            return response()->json(['message' => 'Panier vide.'], 422);
        }

        $frais = $this->livraison->calculerFrais(5, $this->panier->contientFragile());
        $montant_produits = $this->panier->total();
        $calc = $this->commission->calculer($montant_produits);

        $commande = Commande::create([
            'numero' => 'BMJ-' . date('Y') . '-' . strtoupper(Str::random(6)),
            'client_id' => $request->user()->id,
            'entreprise_id' => $data['entreprise_id'],
            'montant_produits' => $montant_produits,
            'frais_livraison' => $frais,
            'montant_total' => $montant_produits + $frais,
            'commission_bmje' => $calc['commission'],
            'montant_entreprise' => $calc['montant_entreprise'],
            'adresse_livraison' => $data['adresse_livraison'],
            'ville_livraison' => $data['ville_livraison'],
            'telephone_livraison' => $data['telephone_livraison'],
            'mode_paiement' => $data['mode_paiement'],
            'notes_client' => $data['notes_client'] ?? null,
            'statut' => 'en_attente',
            'paiement_statut' => 'en_attente',
        ]);

        foreach ($this->panier->contenu() as $item) {
            CommandeProduit::create([
                'commande_id' => $commande->id,
                'produit_id' => $item['produit_id'],
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix'],
                'montant' => $item['prix'] * $item['quantite'],
            ]);
        }

        $this->panier->vider();

        return response()->json([
            'message' => 'Commande passee avec succes.',
            'commande' => $commande,
        ], 201);
    }

    public function commandes(Request $request)
    {
        $commandes = Commande::where('client_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json($commandes);
    }

    public function commandeDetail(Commande $commande, Request $request)
    {
        if ($commande->client_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorise.'], 403);
        }

        $commande->load(['commandeProduits.produit', 'livraison.suivis']);

        return response()->json($commande);
    }

    public function annulerCommande(Commande $commande, Request $request)
    {
        if ($commande->client_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorise.'], 403);
        }

        if (!in_array($commande->statut, ['en_attente', 'confirmee'])) {
            return response()->json(['message' => 'Impossible d\'annuler cette commande.'], 422);
        }

        $commande->update(['statut' => 'annulee']);

        return response()->json(['message' => 'Commande annulee.']);
    }

    public function donnerAvis(Request $request, Commande $commande)
    {
        if ($commande->client_id !== $request->user()->id || $commande->statut !== 'livree') {
            return response()->json(['message' => 'Non autorise.'], 403);
        }

        $data = $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:500',
        ]);

        $commande->update(['note_client' => $data['note']]);

        return response()->json(['message' => 'Merci pour votre avis.'], 201);
    }
}
