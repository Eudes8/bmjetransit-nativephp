<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entreprise;
use App\Models\Forfait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'prenom' => $data['prenom'],
            'nom' => $data['nom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'password' => Hash::make($data['password']),
            'type' => 'client',
            'statut' => 'actif',
        ]);

        return response()->json([
            'message' => 'Inscription reussie.',
            'user' => $user->only(['id', 'prenom', 'nom', 'email', 'telephone', 'type']),
            'token' => $user->createToken('mobile')->plainTextToken,
        ], 201);
    }

    public function registerEntreprise(Request $request)
    {
        $data = $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
            'raison_sociale' => 'required|string|max:200',
            'sigle' => 'nullable|string|max:20',
            'secteur_activite' => 'required|string|max:100',
            'registre_commerce' => 'nullable|string|max:50',
            'ville' => 'required|string|max:100',
            'adresse' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = User::create([
            'prenom' => $data['prenom'],
            'nom' => $data['nom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'password' => Hash::make($data['password']),
            'type' => 'entreprise',
            'statut' => 'actif',
        ]);

        $forfait = Forfait::where('nom', 'Starter')->first();

        Entreprise::create([
            'user_id' => $user->id,
            'forfait_id' => $forfait?->id,
            'raison_sociale' => $data['raison_sociale'],
            'sigle' => $data['sigle'] ?? null,
            'secteur_activite' => $data['secteur_activite'],
            'registre_commerce' => $data['registre_commerce'] ?? null,
            'ville' => $data['ville'],
            'adresse' => $data['adresse'],
            'description' => $data['description'] ?? null,
            'statut' => 'en_attente',
            'commission_taux' => config('bmje.commission_defaut', 10),
        ]);

        return response()->json([
            'message' => 'Inscription entreprise soumise. En attente de validation.',
            'user' => $user->only(['id', 'prenom', 'nom', 'email', 'type']),
            'token' => $user->createToken('mobile')->plainTextToken,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        if ($user->statut !== 'actif') {
            return response()->json(['message' => 'Compte desactive ou suspendu.'], 403);
        }

        $user->update(['derniere_connexion' => now()]);

        return response()->json([
            'message' => 'Connexion reussie.',
            'user' => $user->only(['id', 'prenom', 'nom', 'email', 'telephone', 'type']),
            'token' => $user->createToken('mobile')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Deconnexion reussie.']);
    }

    public function profil(Request $request)
    {
        $user = $request->user();
        $data = $user->only(['id', 'prenom', 'nom', 'email', 'telephone', 'type', 'avatar']);

        if ($user->type === 'entreprise') {
            $data['entreprise'] = $user->entreprise;
        }

        if ($user->type === 'livreur') {
            $data['livreur'] = $user->livreur;
        }

        return response()->json($data);
    }

    public function updateProfil(Request $request)
    {
        $data = $request->validate([
            'prenom' => 'sometimes|string|max:100',
            'nom' => 'sometimes|string|max:100',
            'telephone' => 'sometimes|string|max:20',
        ]);

        $request->user()->update($data);

        return response()->json(['message' => 'Profil mis a jour.', 'user' => $request->user()]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users']);

        return response()->json(['message' => 'Instructions envoyees par SMS.']);
    }
}
