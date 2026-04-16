<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Produit;
use App\Notifications\NouvelleCommande;
use App\Services\CommissionService;
use App\Services\LivraisonService;
use App\Services\PaiementService;
use App\Services\PanierService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function __construct(
        protected PanierService $panier,
        protected LivraisonService $livraison,
        protected CommissionService $commission,
        protected PaiementService $paiement,
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
            'adresse_livraison' => 'required|string|max:255',
            'ville_livraison' => 'required|string|max:100',
            'telephone_livraison' => 'required|string|max:20',
            'mode_paiement' => 'required|in:orange_money,mtn_momo,wave,especes',
            'numero_paiement' => 'required_unless:mode_paiement,especes|nullable|string|max:20',
            'notes_client' => 'nullable|string|max:500',
        ]);

        if ($this->panier->nombreArticles() === 0) {
            return response()->json(['message' => 'Panier vide.'], 422);
        }

        $frais = $this->livraison->calculerFrais(5, $this->panier->contientFragile());
        $total = $this->panier->total() + $frais;

        $commande = Commande::create([
            'user_id' => $request->user()->id,
            'numero' => 'CMD-' . strtoupper(Str::random(8)),
            'montant_produits' => $this->panier->total(),
            'frais_livraison' => $frais,
            'montant_total' => $total,
            'adresse_livraison' => $data['adresse_livraison'],
            'ville_livraison' => $data['ville_livraison'],
            'telephone_livraison' => $data['telephone_livraison'],
            'mode_paiement' => $data['mode_paiement'],
            'notes_client' => $data['notes_client'] ?? null,
            'statut' => 'en_attente',
        ]);

        foreach ($this->panier->contenu() as $item) {
            $calc = $this->commission->calculer($item['prix'] * $item['quantite']);
            CommandeProduit::create([
                'commande_id' => $commande->id,
                'produit_id' => $item['produit_id'],
                'entreprise_id' => $item['entreprise_id'],
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix'],
                'montant' => $item['prix'] * $item['quantite'],
                'commission' => $calc['commission'],
                'montant_entreprise' => $calc['montant_entreprise'],
            ]);
        }

        $this->panier->vider();

        return response()->json([
            'message' => 'Commande passee avec succes.',
            'commande' => $commande->load('produits'),
        ], 201);
    }

    public function commandes(Request $request)
    {
        $commandes = Commande::where('user_id', $request->user()->id)
            ->with('produits')
            ->latest()
            ->paginate(15);

        return response()->json($commandes);
    }

    public function commandeDetail(Commande $commande, Request $request)
    {
        if ($commande->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorise.'], 403);
        }

        $commande->load(['produits.produit', 'livraison.suivis']);

        return response()->json($commande);
    }

    public function annulerCommande(Commande $commande, Request $request)
    {
        if ($commande->user_id !== $request->user()->id) {
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
        if ($commande->user_id !== $request->user()->id || $commande->statut !== 'livree') {
            return response()->json(['message' => 'Non autorise.'], 403);
        }

        $data = $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:500',
        ]);

        $avis = Avis::create([
            'user_id' => $request->user()->id,
            'commande_id' => $commande->id,
            'note' => $data['note'],
            'commentaire' => $data['commentaire'] ?? null,
        ]);

        return response()->json(['message' => 'Merci pour votre avis.', 'avis' => $avis], 201);
    }
}
