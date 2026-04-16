<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
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
            $recherche = $request->recherche;
            $query->where(function ($q) use ($recherche) {
                $q->where('nom', 'like', "%{$recherche}%")
                  ->orWhere('description', 'like', "%{$recherche}%");
            });
        }

        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }

        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        $tri = $request->get('tri', 'recent');
        $query = match ($tri) {
            'prix_asc' => $query->orderBy('prix', 'asc'),
            'prix_desc' => $query->orderBy('prix', 'desc'),
            'populaire' => $query->orderBy('nombre_ventes', 'desc'),
            default => $query->latest(),
        };

        $produits = $query->paginate(24);
        $categories = Categorie::where('actif', true)->orderBy('ordre')->get();

        return view('catalogue.index', compact('produits', 'categories'));
    }

    public function show(Produit $produit)
    {
        $produit->load(['entreprise', 'categorie']);

        $similaires = Produit::where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $produit->id)
            ->where('statut', 'actif')
            ->limit(4)
            ->get();

        return view('catalogue.show', compact('produit', 'similaires'));
    }

    public function entreprise(Entreprise $entreprise)
    {
        $entreprise->load(['produits' => fn ($q) => $q->where('statut', 'actif')->latest()]);

        return view('catalogue.entreprise', compact('entreprise'));
    }
}
