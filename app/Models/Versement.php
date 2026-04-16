<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versement extends Model
{
    use HasFactory;

    protected $fillable = [
        'entreprise_id', 'montant', 'mode', 'numero_compte',
        'reference', 'statut', 'date_demande', 'date_effectue',
        'traite_par', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_demande' => 'datetime',
            'date_effectue' => 'datetime',
        ];
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function traitePar()
    {
        return $this->belongsTo(User::class, 'traite_par');
    }
}
