<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titre', 'description', 'date_echeance', 'statut', 
        'projet_id', 'assignee_id', 'createur_id'
    ];
    
    // Relations
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
    
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
    
    public function createur()
    {
        return $this->belongsTo(User::class, 'createur_id');
    }
    
    public function fichiers()
    {
        return $this->hasMany(Fichier::class);
    }
}