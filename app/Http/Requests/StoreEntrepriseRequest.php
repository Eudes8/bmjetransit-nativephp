<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntrepriseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'raison_sociale' => 'required|string|max:200',
            'sigle' => 'nullable|string|max:20',
            'secteur_activite' => 'required|string|max:100',
            'registre_commerce' => 'nullable|string|max:50',
            'ville' => 'required|string|max:100',
            'adresse' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'raison_sociale.required' => 'La raison sociale est obligatoire.',
            'email.unique' => 'Cet email est deja utilise.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caracteres.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'secteur_activite.required' => 'Le secteur d\'activite est obligatoire.',
            'ville.required' => 'La ville est obligatoire.',
        ];
    }
}
