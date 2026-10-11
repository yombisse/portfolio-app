<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class LocalMediaService
{
    public function upload(UploadedFile $file, string $folder): string
    {
        $path = $file->store($folder, 'public');

        if (! is_string($path) || $path === '') {
            throw new RuntimeException('Le fichier envoyé n’a pas pu être enregistré.');
        }

        return $path;
    }

    public function delete(?string $path): void
    {
        if (! $path || parse_url($path, PHP_URL_SCHEME) !== null) {
            return;
        }

        if (! Storage::disk('public')->delete(ltrim($path, '/'))) {
            throw new RuntimeException('Le fichier enregistré n’a pas pu être supprimé.');
        }
    }
}
