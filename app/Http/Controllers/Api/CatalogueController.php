<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Entreprise;
use App\Models\Produit;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::with(['entreprise', 'categorie'])
            ->where('est_actif', true)
            ->whereHas('entreprise', fn ($q) => $q->where('statut', 'approuvee'));

        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        if ($request->filled('recherche')) {
            $query->where('nom', 'like', '%' . $request->recherche . '%');
        }

        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }

        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        $tri = $request->input('tri', 'recent');
        $query = match ($tri) {
            'prix_asc' => $query->orderBy('prix', 'asc'),
            'prix_desc' => $query->orderBy('prix', 'desc'),
            'populaire' => $query->withCount('commandeProduits')->orderByDesc('commande_produits_count'),
            default => $query->latest(),
        };

        return response()->json($query->paginate(20));
    }

    public function show(Produit $produit)
    {
        $produit->load(['entreprise', 'categorie', 'avis.user']);

        return response()->json([
            'produit' => $produit,
            'note_moyenne' => $produit->avis->avg('note'),
            'nombre_avis' => $produit->avis->count(),
        ]);
    }

    public function entreprise(Entreprise $entreprise)
    {
        $entreprise->load(['produits' => fn ($q) => $q->where('est_actif', true)->latest()]);

        return response()->json($entreprise);
    }

    public function categories()
    {
        $categories = Categorie::withCount(['produits' => fn ($q) => $q->where('est_actif', true)])
            ->orderBy('nom')
            ->get();

        return response()->json($categories);
    }

    public function tracking(string $numero)
    {
        $commande = Commande::where('numero', $numero)
            ->with(['livraison.suivis', 'produits'])
            ->first();

        if (!$commande) {
            return response()->json(['message' => 'Commande introuvable.'], 404);
        }

        return response()->json([
            'numero' => $commande->numero,
            'statut' => $commande->statut,
            'livraison' => $commande->livraison,
            'suivis' => $commande->livraison?->suivis,
        ]);
    }
}
