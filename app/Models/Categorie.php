<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'description', 'icone', 'parent_id', 'ordre', 'actif',
    ];

    protected function casts(): array
    {
        return ['actif' => 'boolean'];
    }

    public function parent()
    {
        return $this->belongsTo(Categorie::class, 'parent_id');
    }

    public function sousCategories()
    {
        return $this->hasMany(Categorie::class, 'parent_id');
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
