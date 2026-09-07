<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competence extends Model
{
    protected $table = 'competences';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'categorie',
        'niveau',
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_competence', 'competence_id', 'project_id');
    }
}
