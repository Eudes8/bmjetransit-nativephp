<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Livreur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LivreurController extends Controller
{
    public function index(Request $request)
    {
        $query = Livreur::with('user');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('disponible')) {
            $query->where('disponible', $request->boolean('disponible'));
        }

        $livreurs = $query->latest()->paginate(20);

        return view('admin.livreurs.index', compact('livreurs'));
    }

    public function create()
    {
        return view('admin.livreurs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|unique:users',
            'numero_cni' => 'nullable|string',
            'permis_conduire' => 'nullable|string',
            'type_vehicule' => 'required|in:moto,velo,camionnette,camion',
            'immatriculation' => 'nullable|string',
            'zone_activite' => 'required|string',
            'salaire_mensuel' => 'required|integer|min:0',
            'prime_par_course' => 'required|integer|min:0',
        ]);

        $user = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'password' => Hash::make('livreur123'),
            'type' => 'livreur',
        ]);

        Livreur::create([
            'user_id' => $user->id,
            'numero_cni' => $data['numero_cni'] ?? null,
            'permis_conduire' => $data['permis_conduire'] ?? null,
            'type_vehicule' => $data['type_vehicule'],
            'immatriculation' => $data['immatriculation'] ?? null,
            'zone_activite' => $data['zone_activite'],
            'salaire_mensuel' => $data['salaire_mensuel'],
            'prime_par_course' => $data['prime_par_course'],
        ]);

        return redirect()->route('admin.livreurs.index')
            ->with('success', 'Livreur ajoute. Mot de passe par defaut : livreur123');
    }

    public function show(Livreur $livreur)
    {
        $livreur->load([
            'user',
            'livraisons' => fn ($q) => $q->latest()->limit(20),
            'livraisons.commande',
        ]);

        return view('admin.livreurs.show', compact('livreur'));
    }

    public function toggleDisponibilite(Livreur $livreur)
    {
        $livreur->update(['disponible' => !$livreur->disponible]);

        return back()->with('success', 'Disponibilite mise a jour.');
    }

    public function changerStatut(Request $request, Livreur $livreur)
    {
        $request->validate([
            'statut' => 'required|in:actif,inactif,suspendu',
        ]);

        $livreur->update(['statut' => $request->statut]);

        return back()->with('success', 'Statut du livreur mis a jour.');
    }
}
