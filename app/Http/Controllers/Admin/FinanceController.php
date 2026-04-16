<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Versement;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        // Statistiques financieres
        $stats = [
            'ca_total' => Transaction::where('type', 'commission_bmje')
                ->where('statut', 'reussie')->sum('montant'),
            'ca_mois' => Transaction::where('type', 'commission_bmje')
                ->where('statut', 'reussie')
                ->whereMonth('date_transaction', now()->month)
                ->whereYear('date_transaction', now()->year)
                ->sum('montant'),
            'abonnements_mois' => Transaction::where('type', 'abonnement')
                ->where('statut', 'reussie')
                ->whereMonth('date_transaction', now()->month)
                ->sum('montant'),
            'livraisons_mois' => Transaction::where('type', 'paiement_client')
                ->where('statut', 'reussie')
                ->whereMonth('date_transaction', now()->month)
                ->sum('montant'),
            'versements_en_attente' => Versement::where('statut', 'en_attente')->count(),
            'total_a_verser' => Versement::where('statut', 'en_attente')->sum('montant'),
        ];

        // Transactions recentes
        $transactions = Transaction::with(['commande', 'deUser', 'versUser'])
            ->latest('date_transaction')
            ->paginate(30);

        // Versements en attente
        $versements = Versement::with('entreprise.proprietaire')
            ->where('statut', 'en_attente')
            ->latest('date_demande')
            ->get();

        return view('admin.finances.index', compact('stats', 'transactions', 'versements'));
    }

    public function effectuerVersement(Request $request, Versement $versement)
    {
        $request->validate([
            'reference' => 'required|string',
        ]);

        $versement->update([
            'statut' => 'effectue',
            'reference' => $request->reference,
            'date_effectue' => now(),
            'traite_par' => auth()->id(),
        ]);

        $versement->entreprise->portefeuille->debiter($versement->montant);

        Transaction::create([
            'type' => 'reversement_entreprise',
            'montant' => $versement->montant,
            'vers_user_id' => $versement->entreprise->user_id,
            'mode' => $versement->mode,
            'reference' => $request->reference,
            'statut' => 'reussie',
            'date_transaction' => now(),
        ]);

        return back()->with('success', 'Versement effectue.');
    }
}
