<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'entreprise';
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'categorie_id' => 'required|exists:categories,id',
            'prix' => 'required|numeric|min:100',
            'prix_promo' => 'nullable|numeric|min:0|lt:prix',
            'stock' => 'nullable|integer|min:0',
            'poids_kg' => 'nullable|numeric|min:0',
            'est_fragile' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du produit est obligatoire.',
            'categorie_id.required' => 'Veuillez choisir une categorie.',
            'categorie_id.exists' => 'Categorie non valide.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.min' => 'Le prix minimum est de 100 XOF.',
            'prix_promo.lt' => 'Le prix promo doit etre inferieur au prix normal.',
        ];
    }
}
