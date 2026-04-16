<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $entreprise = auth()->user()->entreprise;

        $query = $entreprise->commandes()->with(['client', 'livraison']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $commandes = $query->latest()->paginate(20);

        return view('entreprise.commandes.index', compact('commandes'));
    }

    public function show(Commande $commande)
    {
        $this->autoriser($commande);

        $commande->load([
            'client', 'commandeProduits.produit',
            'livraison.livreur.user', 'livraison.suivis',
        ]);

        return view('entreprise.commandes.show', compact('commande'));
    }

    public function confirmer(Commande $commande)
    {
        $this->autoriser($commande);

        if ($commande->statut !== 'en_attente') {
            return back()->with('error', 'Cette commande ne peut pas etre confirmee.');
        }

        $commande->update(['statut' => 'confirmee']);

        return back()->with('success', 'Commande confirmee.');
    }

    public function marquerPrete(Commande $commande)
    {
        $this->autoriser($commande);

        if (!in_array($commande->statut, ['confirmee', 'en_preparation'])) {
            return back()->with('error', 'Cette commande ne peut pas etre marquee comme prete.');
        }

        $commande->update(['statut' => 'prete']);

        return back()->with('success', 'Commande marquee comme prete pour enlevement.');
    }

    private function autoriser(Commande $commande): void
    {
        if ($commande->entreprise_id !== auth()->user()->entreprise->id) {
            abort(403);
        }
    }
}
