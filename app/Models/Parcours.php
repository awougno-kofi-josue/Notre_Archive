<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcours extends Model
{
    protected $fillable = ['nom'];

    public function niveaux()
    {
        return $this->hasMany(Niveau::class);
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
