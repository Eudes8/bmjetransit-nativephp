<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero', 'client_id', 'entreprise_id',
        'montant_produits', 'frais_livraison', 'montant_total',
        'commission_bmje', 'montant_entreprise',
        'adresse_livraison', 'ville_livraison', 'telephone_livraison',
        'lat_livraison', 'lng_livraison',
        'statut', 'mode_paiement', 'paiement_statut', 'notes_client',
    ];

    // ── Relations ─────────────────────────────────────

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function commandeProduits()
    {
        return $this->hasMany(CommandeProduit::class);
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commande_produits')
                     ->withPivot('quantite', 'prix_unitaire', 'montant');
    }

    public function livraison()
    {
        return $this->hasOne(Livraison::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    // ── Auto-numérotation ─────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Commande $commande) {
            if (empty($commande->numero)) {
                $annee = now()->format('Y');
                $dernier = static::whereYear('created_at', $annee)->count() + 1;
                $commande->numero = sprintf('BMJ-%s-%05d', $annee, $dernier);
            }
        });
    }

    // ── Helpers ───────────────────────────────────────

    public function estPayee(): bool
    {
        return $this->paiement_statut === 'paye';
    }

    public function estLivree(): bool
    {
        return $this->statut === 'livree';
    }

    public function peutEtreAnnulee(): bool
    {
        return in_array($this->statut, ['en_attente', 'confirmee']);
    }
}
