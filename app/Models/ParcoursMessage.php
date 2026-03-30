<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcoursMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'parcours_id',
        'titre',
        'message',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function parcours()
    {
        return $this->belongsTo(Parcours::class);
    }
}
