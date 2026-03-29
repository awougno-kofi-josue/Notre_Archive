<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly; // Importation du Trait

class Document extends Model
{
    use MediaAlly; // <--- AJOUTE CETTE LIGNE ICI pour activer Cloudinary

    protected $fillable = [
        'titre', 
        'description', 
        'fichier', 
        'niveau_id', 
        'parcours_id', 
        'user_id'
    ];
    
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function parcours()
    {
        return $this->belongsTo(Parcours::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}