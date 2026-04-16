<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forfait extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'description', 'prix_mensuel', 'prix_annuel',
        'max_produits', 'a_statistiques', 'a_api', 'a_priorite', 'actif',
    ];

    protected function casts(): array
    {
        return [
            'a_statistiques' => 'boolean',
            'a_api' => 'boolean',
            'a_priorite' => 'boolean',
            'actif' => 'boolean',
        ];
    }

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }

    public function estGratuit(): bool
    {
        return $this->prix_mensuel === 0;
    }
}
