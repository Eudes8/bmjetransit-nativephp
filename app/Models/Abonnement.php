<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'entreprise_id', 'forfait_id', 'date_debut', 'date_fin',
        'statut', 'montant_paye', 'mode_paiement', 'renouvellement_auto',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'renouvellement_auto' => 'boolean',
        ];
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function forfait()
    {
        return $this->belongsTo(Forfait::class);
    }

    public function estActif(): bool
    {
        return $this->statut === 'actif' && $this->date_fin->isFuture();
    }

    public function estExpire(): bool
    {
        return $this->date_fin->isPast();
    }
}
