<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['name', 'description', 'image_url', 'type', 'conditions'];

    protected $casts = [
        'conditions' => 'array'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }
}