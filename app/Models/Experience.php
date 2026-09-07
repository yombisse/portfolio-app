<?php

namespace App\Models;

use App\Casts\PostgresTextArray;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Experience extends Model
{
    protected $table = 'experiences';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'titre',
        'entreprise',
        'lieu',
        'contact_entreprise',
        'date_debut',
        'date_fin',
        'bilan',
        'justificatif',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'bilan' => PostgresTextArray::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (Experience $experience): void {
            if (! $experience->getKey()) {
                $experience->setAttribute($experience->getKeyName(), (string) Str::uuid());
            }
        });
    }
}
