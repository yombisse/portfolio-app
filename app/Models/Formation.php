<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Formation extends Model
{
    protected $table = 'formations';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'institut',
        'domaine',
        'diplome',
        'description',
        'date_debut',
        'date_fin',
        'justificatif',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Formation $formation): void {
            if (! $formation->getKey()) {
                $formation->setAttribute($formation->getKeyName(), (string) Str::uuid());
            }
        });
    }
}
