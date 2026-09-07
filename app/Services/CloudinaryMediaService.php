<?php

namespace App\Services;

use Cloudinary\Uploader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CloudinaryMediaService
{
    public function __construct()
    {
        \Cloudinary::config_from_url((string) config('services.cloudinary.url'));
    }

    public function upload(UploadedFile $file, string $folder): string
    {
        $result = Uploader::upload($file->getRealPath(), [
            'folder' => $folder,
            'resource_type' => 'auto',
            'unique_filename' => true,
            'overwrite' => false,
        ]);

        $url = $result['secure_url'] ?? null;

        if (! is_string($url) || $url === '') {
            throw new RuntimeException('Cloudinary n’a pas retourné une URL sécurisée pour le fichier envoyé.');
        }

        return $this->optimizedDeliveryUrl($url, $file);
    }

    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);

            return;
        }

        $asset = $this->assetFromUrl($path);

        if ($asset) {
            Uploader::destroy($asset['public_id'], [
                'resource_type' => $asset['resource_type'],
                'invalidate' => true,
            ]);
        }
    }

    /**
     * @return array{public_id: string, resource_type: string}|null
     */
    private function assetFromUrl(string $url): ?array
    {
        $path = parse_url($url, PHP_URL_PATH);

        if (! is_string($path) || ! preg_match('#/([^/]+)/upload/(?:[^/]+/)*v\d+/(.+)$#', $path, $matches)) {
            return null;
        }

        $publicId = $matches[2];
        $resourceType = $matches[1];

        if ($resourceType !== 'raw') {
            $publicId = (string) preg_replace('#\.[^./]+$#', '', $publicId);
        }

        return [
            'public_id' => $publicId,
            'resource_type' => $resourceType,
        ];
    }

    private function optimizedDeliveryUrl(string $url, UploadedFile $file): string
    {
        if (! str_starts_with((string) $file->getMimeType(), 'image/')) {
            return $url;
        }

        return str_replace(
            '/image/upload/',
            '/image/upload/f_auto,q_auto,w_1200,c_limit/',
            $url
        );
    }
}
