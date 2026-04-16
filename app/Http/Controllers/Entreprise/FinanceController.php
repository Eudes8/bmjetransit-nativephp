<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Versement;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $entreprise = auth()->user()->entreprise;
        $entreprise->load('portefeuille');

        $stats = [
            'solde_disponible' => $entreprise->portefeuille->solde_disponible ?? 0,
            'solde_en_attente' => $entreprise->portefeuille->solde_en_attente ?? 0,
            'total_gagne' => $entreprise->portefeuille->total_gagne ?? 0,
            'total_retire' => $entreprise->portefeuille->total_retire ?? 0,
        ];

        $versements = Versement::where('entreprise_id', $entreprise->id)
            ->latest('date_demande')
            ->paginate(15);

        $commandes_payees = $entreprise->commandes()
            ->where('statut', 'livree')
            ->latest()
            ->limit(20)
            ->get();

        return view('entreprise.finances', compact('stats', 'versements', 'commandes_payees'));
    }

    public function demanderVersement(Request $request)
    {
        $entreprise = auth()->user()->entreprise;
        $portefeuille = $entreprise->portefeuille;

        $request->validate([
            'montant' => 'required|integer|min:5000',
            'mode' => 'required|in:orange_money,mtn_momo,wave,virement',
            'numero_compte' => 'required|string',
        ]);

        if ($request->montant > $portefeuille->solde_disponible) {
            return back()->with('error', 'Solde insuffisant.');
        }

        Versement::create([
            'entreprise_id' => $entreprise->id,
            'montant' => $request->montant,
            'mode' => $request->mode,
            'numero_compte' => $request->numero_compte,
            'date_demande' => now(),
        ]);

        // Bloquer le montant
        $portefeuille->decrement('solde_disponible', $request->montant);
        $portefeuille->increment('solde_en_attente', $request->montant);

        return back()->with('success', 'Demande de versement envoyee.');
    }
}
