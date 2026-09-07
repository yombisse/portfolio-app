<?php

namespace App\Models;

use App\Casts\PostgresTextArray;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $table = 'projects';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'titre',
        'description',
        'fonctionnalites',
        'role',
        'image_principale',
        'captures',
        'github_url',
        'url_projet',
        'date_projet',
        'publie',
    ];

    protected $casts = [
        'fonctionnalites' => PostgresTextArray::class,
        'captures' => PostgresTextArray::class,
        'date_projet' => 'date',
        'publie' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Project $project): void {
            if (! $project->getKey()) {
                $project->setAttribute($project->getKeyName(), (string) Str::uuid());
            }
        });
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'project_competence', 'project_id', 'competence_id');
    }
}
