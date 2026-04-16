<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuiviLivraison extends Model
{
    use HasFactory;

    protected $table = 'suivi_livraisons';

    protected $fillable = [
        'livraison_id', 'statut', 'description',
        'latitude', 'longitude', 'horodatage', 'created_by',
    ];

    protected function casts(): array
    {
        return ['horodatage' => 'datetime'];
    }

    public function livraison()
    {
        return $this->belongsTo(Livraison::class);
    }

    public function creePar()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
