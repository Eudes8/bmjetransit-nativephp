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
            ->where('statut', 'actif')
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
            'populaire' => $query->orderByDesc('nombre_ventes'),
            default => $query->latest(),
        };

        return response()->json($query->paginate(20));
    }

    public function show(Produit $produit)
    {
        $produit->load(['entreprise', 'categorie']);

        return response()->json([
            'produit' => $produit,
            'note_moyenne' => $produit->note_moyenne,
            'nombre_ventes' => $produit->nombre_ventes,
        ]);
    }

    public function entreprise(Entreprise $entreprise)
    {
        $entreprise->load(['produits' => fn ($q) => $q->where('statut', 'actif')->latest()]);

        return response()->json($entreprise);
    }

    public function categories()
    {
        $categories = Categorie::where('actif', true)
            ->withCount(['produits' => fn ($q) => $q->where('statut', 'actif')])
            ->orderBy('ordre')
            ->get();

        return response()->json($categories);
    }

    public function tracking(string $numero)
    {
        $commande = Commande::where('numero', $numero)
            ->with(['livraison.suivis'])
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
