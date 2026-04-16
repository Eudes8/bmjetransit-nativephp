<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $entreprise = auth()->user()->entreprise;

        if (!$entreprise || $entreprise->statut !== 'approuvee') {
            return view('entreprise.en-attente', compact('entreprise'));
        }

        $entreprise->load('portefeuille');

        $stats = [
            'total_produits' => $entreprise->produits()->count(),
            'produits_actifs' => $entreprise->produits()->where('statut', 'actif')->count(),
            'commandes_en_cours' => $entreprise->commandes()
                ->whereNotIn('statut', ['livree', 'annulee'])->count(),
            'commandes_mois' => $entreprise->commandes()
                ->whereMonth('created_at', now()->month)->count(),
            'ca_mois' => $entreprise->commandes()
                ->where('statut', 'livree')
                ->whereMonth('created_at', now()->month)
                ->sum('montant_entreprise'),
            'solde_disponible' => $entreprise->portefeuille->solde_disponible ?? 0,
        ];

        $dernieres_commandes = $entreprise->commandes()
            ->with('client')
            ->latest()
            ->limit(10)
            ->get();

        return view('entreprise.dashboard', compact('entreprise', 'stats', 'dernieres_commandes'));
    }

    public function enAttente()
    {
        $entreprise = auth()->user()->entreprise;
        return view('entreprise.en-attente', compact('entreprise'));
    }
}
