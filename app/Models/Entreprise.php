<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'raison_sociale', 'sigle', 'description', 'logo',
        'registre_commerce', 'numero_contribuable', 'secteur_activite',
        'adresse', 'ville', 'pays', 'telephone', 'email',
        'statut', 'commission_taux',
    ];

    // ── Relations ─────────────────────────────────────

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }

    public function abonnementActif()
    {
        return $this->hasOne(Abonnement::class)->where('statut', 'actif');
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function portefeuille()
    {
        return $this->hasOne(PortefeuilleEntreprise::class);
    }

    public function versements()
    {
        return $this->hasMany(Versement::class);
    }

    // ── Helpers ───────────────────────────────────────

    public function estApprouvee(): bool
    {
        return $this->statut === 'approuvee';
    }

    public function aUnAbonnementActif(): bool
    {
        return $this->abonnementActif()->exists();
    }
}
