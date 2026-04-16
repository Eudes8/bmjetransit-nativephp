<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    public function index(Request $request)
    {
        $query = Entreprise::with(['proprietaire', 'abonnementActif.forfait']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('recherche')) {
            $recherche = $request->recherche;
            $query->where(function ($q) use ($recherche) {
                $q->where('raison_sociale', 'like', "%{$recherche}%")
                  ->orWhere('sigle', 'like', "%{$recherche}%");
            });
        }

        $entreprises = $query->latest()->paginate(20);

        return view('admin.entreprises.index', compact('entreprises'));
    }

    public function show(Entreprise $entreprise)
    {
        $entreprise->load([
            'proprietaire', 'abonnements.forfait', 'produits',
            'commandes' => fn ($q) => $q->latest()->limit(10),
            'portefeuille',
        ]);

        return view('admin.entreprises.show', compact('entreprise'));
    }

    public function approuver(Entreprise $entreprise)
    {
        $entreprise->update(['statut' => 'approuvee']);

        return redirect()->route('admin.entreprises.show', $entreprise)
            ->with('success', 'Entreprise approuvee.');
    }

    public function suspendre(Entreprise $entreprise)
    {
        $entreprise->update(['statut' => 'suspendue']);

        return redirect()->route('admin.entreprises.show', $entreprise)
            ->with('success', 'Entreprise suspendue.');
    }

    public function rejeter(Entreprise $entreprise)
    {
        $entreprise->update(['statut' => 'rejetee']);

        return redirect()->route('admin.entreprises.show', $entreprise)
            ->with('success', 'Entreprise rejetee.');
    }

    public function modifierCommission(Request $request, Entreprise $entreprise)
    {
        $request->validate([
            'commission_taux' => 'required|numeric|min:0|max:50',
        ]);

        $entreprise->update(['commission_taux' => $request->commission_taux]);

        return redirect()->route('admin.entreprises.show', $entreprise)
            ->with('success', 'Commission mise a jour.');
    }
}
