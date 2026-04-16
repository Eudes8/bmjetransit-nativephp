<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLivreurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'type_vehicule' => 'required|in:moto,velo,voiture,camionnette',
            'numero_permis' => 'nullable|string|max:50',
            'zone_activite' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Cet email est deja utilise.',
            'type_vehicule.in' => 'Type de vehicule non valide.',
            'zone_activite.required' => 'La zone d\'activite est obligatoire.',
        ];
    }
}
