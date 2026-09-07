<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@portfolio.test'],
            [
                'name' => 'Admin Portfolio',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

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
