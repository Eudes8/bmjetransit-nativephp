<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Livraison;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function show(string $numero)
    {
        $livraison = Livraison::with(['commande.client', 'livreur.user', 'suivis'])
            ->where('numero_tracking', $numero)
            ->firstOrFail();

        return view('tracking.show', compact('livraison'));
    }

    public function rechercher(Request $request)
    {
        $request->validate([
            'numero' => 'required|string',
        ]);

        return redirect()->route('tracking', $request->numero);
    }
}
