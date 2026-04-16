<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortefeuilleEntreprise extends Model
{
    use HasFactory;

    protected $table = 'portefeuilles_entreprises';

    protected $fillable = [
        'entreprise_id', 'solde_disponible', 'solde_en_attente',
        'total_gagne', 'total_retire',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function crediter(int $montant): void
    {
        $this->increment('solde_disponible', $montant);
        $this->increment('total_gagne', $montant);
    }

    public function debiter(int $montant): void
    {
        $this->decrement('solde_disponible', $montant);
        $this->increment('total_retire', $montant);
    }
}
