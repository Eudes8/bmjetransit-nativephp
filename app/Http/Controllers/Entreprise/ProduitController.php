<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        $entreprise = auth()->user()->entreprise;

        $produits = $entreprise->produits()
            ->with('categorie')
            ->latest()
            ->paginate(20);

        return view('entreprise.produits.index', compact('produits'));
    }

    public function create()
    {
        $categories = Categorie::where('actif', true)->orderBy('ordre')->get();
        return view('entreprise.produits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $entreprise = auth()->user()->entreprise;

        // Verifier la limite du forfait
        $abonnement = $entreprise->abonnementActif;
        if ($abonnement) {
            $max = $abonnement->forfait->max_produits;
            $actuel = $entreprise->produits()->count();
            if ($actuel >= $max) {
                return back()->with('error', "Limite atteinte ($max produits). Passez a un forfait superieur.");
            }
        }

        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'prix' => 'required|integer|min:100',
            'prix_promo' => 'nullable|integer|min:0',
            'stock' => 'nullable|integer|min:0',
            'poids_kg' => 'nullable|numeric|min:0',
            'est_fragile' => 'boolean',
        ]);

        $entreprise->produits()->create($data);

        return redirect()->route('espace.produits.index')
            ->with('success', 'Produit cree.');
    }

    public function edit(Produit $produit)
    {
        $this->autoriser($produit);
        $categories = Categorie::where('actif', true)->orderBy('ordre')->get();

        return view('entreprise.produits.edit', compact('produit', 'categories'));
    }

    public function update(Request $request, Produit $produit)
    {
        $this->autoriser($produit);

        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'prix' => 'required|integer|min:100',
            'prix_promo' => 'nullable|integer|min:0',
            'en_promo' => 'boolean',
            'stock' => 'nullable|integer|min:0',
            'poids_kg' => 'nullable|numeric|min:0',
            'est_fragile' => 'boolean',
            'statut' => 'required|in:actif,inactif,en_rupture',
        ]);

        $produit->update($data);

        return redirect()->route('espace.produits.index')
            ->with('success', 'Produit mis a jour.');
    }

    public function destroy(Produit $produit)
    {
        $this->autoriser($produit);

        if ($produit->commandeProduits()->exists()) {
            $produit->update(['statut' => 'inactif']);
            return back()->with('success', 'Produit desactive (commandes existantes).');
        }

        $produit->delete();
        return redirect()->route('espace.produits.index')
            ->with('success', 'Produit supprime.');
    }

    private function autoriser(Produit $produit): void
    {
        if ($produit->entreprise_id !== auth()->user()->entreprise->id) {
            abort(403);
        }
    }
}
