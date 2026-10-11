<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $name = config('services.admin.name');
        $email = config('services.admin.email');
        $password = config('services.admin.password');

        if (! is_string($name) || trim($name) === ''
            || ! is_string($email) || trim($email) === ''
            || ! is_string($password) || $password === '') {
            throw new RuntimeException('Configurez ADMIN_NAME, ADMIN_EMAIL et ADMIN_PASSWORD avant de lancer les seeders.');
        }

        $admin = User::query()->where('email', $email)->first()
            ?? User::query()->where('email', 'admin@portfolio.test')->first()
            ?? new User;

        $admin->forceFill([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'email_verified_at' => now(),
        ])->save();

        $technologies = [
            ['nom' => 'Laravel', 'niveau' => 'expert'],
            ['nom' => 'React', 'niveau' => 'avancé'],
            ['nom' => 'Vue.js', 'niveau' => 'avancé'],
            ['nom' => 'PostgreSQL', 'niveau' => 'avancé'],
            ['nom' => 'Tailwind CSS', 'niveau' => 'avancé'],
            ['nom' => 'Git', 'niveau' => 'expert'],
        ];

        foreach ($technologies as $technology) {
            Competence::updateOrCreate(
                [
                    'nom' => $technology['nom'],
                    'categorie' => 'technologie',
                ],
                [
                    'niveau' => $technology['niveau'],
                ]
            );
        }

        $aptitudes = [
            ['nom' => 'Leadership'],
            ['nom' => 'Communication'],
            ['nom' => 'Esprit d’équipe'],
        ];

        foreach ($aptitudes as $aptitude) {
            Competence::updateOrCreate(
                ['nom' => $aptitude['nom'], 'categorie' => 'aptitude'],
                ['niveau' => null]
            );
        }
    }
}
