<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titre', 'description', 'date_debut', 'date_fin', 'statut', 'createur_id'
    ];
    
    // Relations
    public function createur()
    {
        return $this->belongsTo(User::class, 'createur_id');
    }
    
    public function membres()
    {
        return $this->belongsToMany(User::class, 'utilisateur_projet')
                    ->withPivot('role')
                    ->withTimestamps();
    }
    
    public function taches()
    {
        return $this->hasMany(Tache::class);
    }
    
    // Méthodes utilitaires
    public function calculerProgression()
    {
        $total = $this->taches()->count();
        if ($total === 0) return 0;
        
        $terminees = $this->taches()->where('statut', 'termine')->count();
        return round(($terminees / $total) * 100);
    }
}