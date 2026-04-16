<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\PortefeuilleEntreprise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ── Connexion ─────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Identifiants incorrects.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = Auth::user();
        $user->update(['derniere_connexion' => now()]);

        return match ($user->type) {
            'admin' => redirect()->route('admin.dashboard'),
            'entreprise' => redirect()->route('espace.dashboard'),
            'livreur' => redirect()->route('livreur.dashboard'),
            default => redirect()->route('client.commandes'),
        };
    }

    // ── Inscription Client ────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'type' => 'client',
        ]);

        Auth::login($user);

        return redirect()->route('catalogue');
    }

    // ── Inscription Entreprise ────────────────────────

    public function showRegisterEntreprise()
    {
        return view('auth.register-entreprise');
    }

    public function registerEntreprise(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'raison_sociale' => 'required|string|max:255',
            'sigle' => 'nullable|string|max:50',
            'secteur_activite' => 'required|string|max:255',
            'registre_commerce' => 'nullable|string',
            'adresse' => 'required|string',
            'ville' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $user = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'password' => Hash::make($data['password']),
            'type' => 'entreprise',
        ]);

        $entreprise = Entreprise::create([
            'user_id' => $user->id,
            'raison_sociale' => $data['raison_sociale'],
            'sigle' => $data['sigle'] ?? null,
            'secteur_activite' => $data['secteur_activite'],
            'registre_commerce' => $data['registre_commerce'] ?? null,
            'adresse' => $data['adresse'],
            'ville' => $data['ville'],
            'description' => $data['description'] ?? null,
            'statut' => 'en_attente',
        ]);

        PortefeuilleEntreprise::create([
            'entreprise_id' => $entreprise->id,
        ]);

        Auth::login($user);

        return redirect()->route('espace.dashboard');
    }

    // ── Deconnexion ───────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
