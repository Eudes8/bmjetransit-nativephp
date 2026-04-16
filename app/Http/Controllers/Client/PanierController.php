<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Services\PanierService;
use App\Services\LivraisonService;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    public function __construct(
        protected PanierService $panier,
        protected LivraisonService $livraison
    ) {}

    public function index()
    {
        return view('client.panier', [
            'panier' => $this->panier->contenu(),
            'parEntreprise' => $this->panier->parEntreprise(),
            'total' => $this->panier->total(),
            'nombre' => $this->panier->nombreArticles(),
        ]);
    }

    public function ajouter(Request $request, Produit $produit)
    {
        $request->validate(['quantite' => 'integer|min:1|max:99']);

        $this->panier->ajouter($produit, $request->input('quantite', 1));

        return back()->with('success', $produit->nom . ' ajoute au panier.');
    }

    public function modifier(Request $request, int $produit_id)
    {
        $request->validate(['quantite' => 'required|integer|min:0|max:99']);

        $this->panier->modifierQuantite($produit_id, $request->quantite);

        return back()->with('success', 'Panier mis a jour.');
    }

    public function supprimer(int $produit_id)
    {
        $this->panier->supprimer($produit_id);

        return back()->with('success', 'Produit retire du panier.');
    }

    public function vider()
    {
        $this->panier->vider();

        return back()->with('success', 'Panier vide.');
    }

    public function checkout()
    {
        if ($this->panier->nombreArticles() === 0) {
            return redirect()->route('catalogue')->with('error', 'Votre panier est vide.');
        }

        $frais = $this->livraison->calculerFrais(5, $this->panier->contientFragile());

        return view('client.checkout', [
            'panier' => $this->panier->contenu(),
            'parEntreprise' => $this->panier->parEntreprise(),
            'total_produits' => $this->panier->total(),
            'frais_livraison' => $frais,
            'total' => $this->panier->total() + $frais,
            'zones' => config('bmje.zones'),
        ]);
    }
}
