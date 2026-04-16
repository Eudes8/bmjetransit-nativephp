<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livraison;
use App\Models\SuiviLivraison;
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
                'en_cours' => $l->livraisons()->whereIn('statut', ['assignee', 'enlevee', 'en_route'])->count(),
                'total_livrees' => $l->livraisons()->where('statut', 'livree')->count(),
                'disponible' => $l->disponible,
                'nombre_courses' => $l->nombre_courses,
            ],
        ]);
    }

    public function livraisons(Request $request)
    {
        $livraisons = $this->livreur($request)
            ->livraisons()
            ->with(['commande.client'])
            ->latest()
            ->paginate(15);

        return response()->json($livraisons);
    }

    public function detail(Request $request, Livraison $livraison)
    {
        if ($livraison->livreur_id !== $this->livreur($request)->id) {
            return response()->json(['message' => 'Non autorise.'], 403);
        }

        $livraison->load(['commande.client', 'commande.commandeProduits.produit', 'suivis']);

        return response()->json($livraison);
    }

    public function accepter(Request $request, Livraison $livraison)
    {
        if ($livraison->livreur_id !== $this->livreur($request)->id || $livraison->statut !== 'assignee') {
            return response()->json(['message' => 'Action impossible.'], 422);
        }

        $this->livreur($request)->update(['en_course' => true]);
        $this->ajouterSuivi($livraison, 'assignee', 'Livraison acceptee par le livreur.');

        return response()->json(['message' => 'Livraison acceptee.']);
    }

    public function recuperee(Request $request, Livraison $livraison)
    {
        if ($livraison->livreur_id !== $this->livreur($request)->id || $livraison->statut !== 'assignee') {
            return response()->json(['message' => 'Action impossible.'], 422);
        }

        $livraison->update([
            'statut' => 'enlevee',
            'date_enlevement' => now(),
        ]);
        $livraison->commande->update(['statut' => 'enlevee']);
        $this->ajouterSuivi($livraison, 'enlevee', 'Colis enleve chez le vendeur.');

        return response()->json(['message' => 'Colis enleve.']);
    }

    public function enRoute(Request $request, Livraison $livraison)
    {
        if ($livraison->livreur_id !== $this->livreur($request)->id || $livraison->statut !== 'enlevee') {
            return response()->json(['message' => 'Action impossible.'], 422);
        }

        $livraison->update(['statut' => 'en_route']);
        $livraison->commande->update(['statut' => 'en_livraison']);
        $this->ajouterSuivi($livraison, 'en_route', 'En route vers le client.');

        return response()->json(['message' => 'En route.']);
    }

    public function livree(Request $request, Livraison $livraison)
    {
        if ($livraison->livreur_id !== $this->livreur($request)->id || !in_array($livraison->statut, ['en_route', 'enlevee'])) {
            return response()->json(['message' => 'Action impossible.'], 422);
        }

        $livraison->update([
            'statut' => 'livree',
            'date_livraison_reelle' => now(),
        ]);

        $livraison->commande->update(['statut' => 'livree']);

        $l = $this->livreur($request);
        $l->increment('nombre_courses');
        $l->update(['en_course' => false]);

        $this->ajouterSuivi($livraison, 'livree', 'Colis livre avec succes.');

        return response()->json(['message' => 'Livraison terminee.']);
    }

    public function toggleDisponibilite(Request $request)
    {
        $l = $this->livreur($request);
        $l->update(['disponible' => !$l->disponible]);

        return response()->json([
            'message' => $l->disponible ? 'Vous etes disponible.' : 'Vous etes indisponible.',
            'disponible' => $l->disponible,
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

    protected function ajouterSuivi(Livraison $livraison, string $statut, string $description): void
    {
        SuiviLivraison::create([
            'livraison_id' => $livraison->id,
            'statut' => $statut,
            'description' => $description,
            'horodatage' => now(),
        ]);
    }
}
