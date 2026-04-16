<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Entreprise;
use App\Models\Livreur;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => User::where('type', 'client')->count(),
            'total_entreprises' => Entreprise::count(),
            'entreprises_en_attente' => Entreprise::where('statut', 'en_attente')->count(),
            'total_livreurs' => Livreur::count(),
            'livreurs_disponibles' => Livreur::where('disponible', true)->where('en_course', false)->count(),
            'commandes_jour' => Commande::whereDate('created_at', today())->count(),
            'commandes_en_cours' => Commande::whereNotIn('statut', ['livree', 'annulee'])->count(),
            'ca_jour' => Transaction::where('type', 'commission_bmje')
                ->where('statut', 'reussie')
                ->whereDate('date_transaction', today())
                ->sum('montant'),
            'ca_mois' => Transaction::where('type', 'commission_bmje')
                ->where('statut', 'reussie')
                ->whereMonth('date_transaction', now()->month)
                ->whereYear('date_transaction', now()->year)
                ->sum('montant'),
        ];

        $dernieres_commandes = Commande::with(['client', 'entreprise'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'dernieres_commandes'));
    }
}
