<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'email',
        'sujet',
        'contenu',
        'lu',
        'created_at',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'created_at' => 'datetime',
    ];
}
