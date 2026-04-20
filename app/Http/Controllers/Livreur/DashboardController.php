<?php

namespace App\Http\Controllers\Livreur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Livraison;

class DashboardController extends Controller
{
    public function index()
    {
        $livreur = auth()->user()->livreur;

        if (!$livreur) {
            return redirect()->route('home')->with('error', 'Vous n\'êtes pas enregistré comme livreur.');
        }

        $missions = Livraison::where('livreur_id', $livreur->id)
            ->whereIn('statut', ['assignee', 'en_route'])
            ->with('commande.client')
            ->orderBy('created_at', 'desc')
            ->get();

        $gains_jour = Livraison::where('livreur_id', $livreur->id)
            ->where('statut', 'livree')
            ->whereDate('updated_at', today())
            ->sum('frais_livraison');

        $livraisons_count = Livraison::where('livreur_id', $livreur->id)
            ->where('statut', 'livree')
            ->whereDate('updated_at', today())
            ->count();

        return view('livreur.dashboard', [
            'missions' => $missions,
            'gains_jour' => $gains_jour,
            'livraisons_count' => $livraisons_count,
            'temps_travail' => '5h 20m',
            'bonus_total' => 2400,
            'distance_totale' => 64,
        ]);
    }
}
