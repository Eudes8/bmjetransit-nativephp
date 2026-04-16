<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id', 'type', 'montant',
        'de_user_id', 'vers_user_id',
        'mode', 'reference', 'statut',
        'date_transaction', 'notes',
    ];

    protected function casts(): array
    {
        return ['date_transaction' => 'datetime'];
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function deUser()
    {
        return $this->belongsTo(User::class, 'de_user_id');
    }

    public function versUser()
    {
        return $this->belongsTo(User::class, 'vers_user_id');
    }

    public function getMontantFormatAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' XOF';
    }
}
