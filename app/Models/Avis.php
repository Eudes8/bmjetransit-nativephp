<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $table = 'avis';

    protected $fillable = [
        'commande_id', 'de_user_id', 'vers_user_id',
        'type', 'note', 'commentaire',
    ];

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
}
