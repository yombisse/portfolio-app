<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'titre',
        'bio',
        'photo',
        'localisation',
        'email',
        'telephone',
        'reseaux_sociaux',
        'cv',
        'focus',
        'work_style',
    ];

    protected $casts = [
        'reseaux_sociaux' => 'array',
    ];
}
