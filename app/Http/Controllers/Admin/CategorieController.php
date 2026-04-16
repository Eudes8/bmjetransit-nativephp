<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::withCount('produits')
            ->with('sousCategories')
            ->whereNull('parent_id')
            ->orderBy('ordre')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $ordre = Categorie::max('ordre') + 1;
        Categorie::create([...$data, 'ordre' => $ordre]);

        return back()->with('success', 'Categorie creee.');
    }

    public function update(Request $request, Categorie $categorie)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:50',
            'actif' => 'boolean',
        ]);

        $categorie->update($data);

        return back()->with('success', 'Categorie mise a jour.');
    }

    public function destroy(Categorie $categorie)
    {
        if ($categorie->produits()->exists()) {
            return back()->with('error', 'Impossible de supprimer : des produits utilisent cette categorie.');
        }

        $categorie->delete();

        return back()->with('success', 'Categorie supprimee.');
    }
}
