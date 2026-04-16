<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Livreur;
use App\Models\Livraison;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $query = Commande::with(['client', 'entreprise', 'livraison.livreur']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('recherche')) {
            $recherche = $request->recherche;
            $query->where(function ($q) use ($recherche) {
                $q->where('numero', 'like', "%{$recherche}%")
                  ->orWhereHas('client', fn ($u) => $u->where('nom', 'like', "%{$recherche}%")
                      ->orWhere('prenom', 'like', "%{$recherche}%"));
            });
        }

        $commandes = $query->latest()->paginate(20);

        return view('admin.commandes.index', compact('commandes'));
    }

    public function show(Commande $commande)
    {
        $commande->load([
            'client', 'entreprise', 'commandeProduits.produit',
            'livraison.livreur.user', 'livraison.suivis', 'transactions',
        ]);

        $livreurs_disponibles = Livreur::where('disponible', true)
            ->where('en_course', false)
            ->where('statut', 'actif')
            ->with('user')
            ->get();

        return view('admin.commandes.show', compact('commande', 'livreurs_disponibles'));
    }

    public function assignerLivreur(Request $request, Commande $commande)
    {
        $request->validate([
            'livreur_id' => 'required|exists:livreurs,id',
        ]);

        $livreur = Livreur::findOrFail($request->livreur_id);

        $livraison = Livraison::create([
            'commande_id' => $commande->id,
            'livreur_id' => $livreur->id,
            'adresse_enlevement' => $commande->entreprise->adresse ?? 'A confirmer',
            'adresse_livraison' => $commande->adresse_livraison,
            'prime_livreur' => $livreur->prime_par_course,
            'statut' => 'assignee',
        ]);

        $livreur->update(['en_course' => true]);
        $commande->update(['statut' => 'confirmee']);

        $livraison->ajouterSuivi('assignee', 'Livreur assigne a la commande');

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Livreur assigne avec succes.');
    }

    public function changerStatut(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,confirmee,en_preparation,prete,enlevee,en_livraison,livree,annulee,litige',
        ]);

        $commande->update(['statut' => $request->statut]);

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Statut mis a jour.');
    }
}
