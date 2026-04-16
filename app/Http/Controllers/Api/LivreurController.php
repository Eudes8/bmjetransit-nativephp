<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livraison;
use App\Models\SuiviLivraison;
use App\Notifications\CommandeLivree;
use Illuminate\Http\Request;

class LivreurController extends Controller
{
    protected function livreur(Request $request)
    {
        return $request->user()->livreur;
    }

    public function dashboard(Request $request)
    {
        $l = $this->livreur($request);

        return response()->json([
            'livreur' => $l,
            'stats' => [
                'livraisons_aujourdhui' => $l->livraisons()->whereDate('created_at', today())->count(),
                'en_cours' => $l->livraisons()->whereIn('statut', ['assignee', 'recuperee', 'en_route'])->count(),
                'total_livrees' => $l->livraisons()->where('statut', 'livree')->count(),
                'disponible' => $l->est_disponible,
            ],
        ]);
    }

    public function livraisons(Request $request)
    {
        $livraisons = $this->livreur($request)
            ->livraisons()
            ->with(['commande.user', 'commande.produits'])
            ->latest()
            ->paginate(15);

        return response()->json($livraisons);
    }

    public function detail(Request $request, Livraison $livraison)
    {
        if ($livraison->livreur_id !== $this->livreur($request)->id) {
            return response()->json(['message' => 'Non autorise.'], 403);
        }

        $livraison->load(['commande.user', 'commande.commandeProduits.produit', 'suivis']);

        return response()->json($livraison);
    }

    public function accepter(Request $request, Livraison $livraison)
    {
        if ($livraison->livreur_id !== $this->livreur($request)->id || $livraison->statut !== 'assignee') {
            return response()->json(['message' => 'Action impossible.'], 422);
        }

        $livraison->update(['statut' => 'acceptee']);
        $this->ajouterSuivi($livraison, 'Livraison acceptee par le livreur.');

        return response()->json(['message' => 'Livraison acceptee.']);
    }

    public function recuperee(Request $request, Livraison $livraison)
    {
        if ($livraison->livreur_id !== $this->livreur($request)->id || !in_array($livraison->statut, ['acceptee', 'assignee'])) {
            return response()->json(['message' => 'Action impossible.'], 422);
        }

        $livraison->update(['statut' => 'recuperee']);
        $livraison->commande->update(['statut' => 'en_livraison']);
        $this->ajouterSuivi($livraison, 'Colis recupere chez le vendeur.');

        return response()->json(['message' => 'Colis recupere.']);
    }

    public function enRoute(Request $request, Livraison $livraison)
    {
        if ($livraison->livreur_id !== $this->livreur($request)->id || $livraison->statut !== 'recuperee') {
            return response()->json(['message' => 'Action impossible.'], 422);
        }

        $livraison->update(['statut' => 'en_route']);
        $this->ajouterSuivi($livraison, 'En route vers le client.');

        return response()->json(['message' => 'En route.']);
    }

    public function livree(Request $request, Livraison $livraison)
    {
        if ($livraison->livreur_id !== $this->livreur($request)->id || !in_array($livraison->statut, ['en_route', 'recuperee'])) {
            return response()->json(['message' => 'Action impossible.'], 422);
        }

        $livraison->update([
            'statut' => 'livree',
            'livree_a' => now(),
        ]);

        $livraison->commande->update(['statut' => 'livree']);
        $this->ajouterSuivi($livraison, 'Colis livre avec succes.');

        $livraison->commande->user->notify(new CommandeLivree($livraison->commande));

        return response()->json(['message' => 'Livraison terminee.']);
    }

    public function toggleDisponibilite(Request $request)
    {
        $l = $this->livreur($request);
        $l->update(['est_disponible' => !$l->est_disponible]);

        return response()->json([
            'message' => $l->est_disponible ? 'Vous etes disponible.' : 'Vous etes indisponible.',
            'disponible' => $l->est_disponible,
        ]);
    }

    public function mettreAJourPosition(Request $request)
    {
        $data = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $this->livreur($request)->update([
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);

        return response()->json(['message' => 'Position mise a jour.']);
    }

    protected function ajouterSuivi(Livraison $livraison, string $message): void
    {
        SuiviLivraison::create([
            'livraison_id' => $livraison->id,
            'statut' => $livraison->statut,
            'message' => $message,
            'horodatage' => now(),
        ]);
    }
}
