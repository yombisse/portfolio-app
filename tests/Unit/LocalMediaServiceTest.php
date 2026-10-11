<?php

namespace Tests\Unit;

use App\Services\LocalMediaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LocalMediaServiceTest extends TestCase
{
    public function test_upload_stores_file_on_the_public_disk(): void
    {
        Storage::fake('public');

        $path = (new LocalMediaService)->upload(
            UploadedFile::fake()->create('profile.jpg', 1, 'image/jpeg'),
            'profiles'
        );

        $this->assertStringStartsWith('profiles/', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_delete_removes_local_files_and_leaves_remote_urls_untouched(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profiles/profile.jpg', 'image');

        $service = new LocalMediaService;
        $service->delete('profiles/profile.jpg');

        Storage::disk('public')->assertMissing('profiles/profile.jpg');

        Storage::disk('public')->put('profiles/legacy.jpg', 'image');
        $service->delete('https://cdn.example.test/legacy.jpg');

        Storage::disk('public')->assertExists('profiles/legacy.jpg');
    }
}
