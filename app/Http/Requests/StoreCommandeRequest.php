<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'adresse_livraison' => 'required|string|max:255',
            'ville_livraison' => 'required|string|max:100',
            'telephone_livraison' => 'required|string|max:20',
            'mode_paiement' => 'required|in:orange_money,mtn_momo,wave,especes',
            'numero_paiement' => 'required_unless:mode_paiement,especes|nullable|string|max:20',
            'notes_client' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'adresse_livraison.required' => 'L\'adresse de livraison est obligatoire.',
            'ville_livraison.required' => 'La ville est obligatoire.',
            'telephone_livraison.required' => 'Le telephone est obligatoire.',
            'mode_paiement.required' => 'Veuillez choisir un mode de paiement.',
            'mode_paiement.in' => 'Mode de paiement non valide.',
            'numero_paiement.required_unless' => 'Le numero de paiement est requis.',
        ];
    }
}
