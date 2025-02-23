<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichier extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nom', 'chemin', 'type_mime', 'taille', 'tache_id', 'user_id'
    ];
    
    // Relations
    public function tache()
    {
        return $this->belongsTo(Tache::class);
    }
    
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}