<?php

namespace App\Services;

use App\Models\Competence;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class PortfolioService
{
    public function getProfile(): ?Profile
    {
        $profile = Profile::query()->first();

        if ($profile) {
            return $profile;
        }

        return new Profile([
            'id' => 'demo-profile',
            'nom' => 'Fandie Yombissé',
            'titre' => 'Software Developer & Product Builder',
            'bio' => 'Je conçois et développe des produits numériques à forte valeur ajoutée, avec un intérêt particulier pour les interfaces élégantes, les systèmes fiables et les expériences utilisateurs fluides.',
            'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=900&q=80',
            'localisation' => 'Paris, France',
            'email' => 'hello@portfolio.dev',
            'telephone' => '+33 6 12 34 56 78',
            'reseaux_sociaux' => [
                'github' => 'https://github.com',
                'linkedin' => 'https://linkedin.com',
            ],
            'cv' => '/cv.pdf',
            'focus' => 'Concevoir des logiciels clairs, performants et centrés sur les utilisateurs.',
            'work_style' => 'Méthode itérative, collaboration étroite avec les équipes produit et design.',
        ]);
    }

    public function getPublishedProjects()
    {
        $projects = Project::query()
            ->where('publie', true)
            ->with('competences')
            ->orderBy('date_projet', 'desc')
            ->get();

        if ($projects->isNotEmpty()) {
            return $projects;
        }

        return $this->buildDemoProjects();
    }

    public function getProjects()
    {
        $projects = Project::query()
            ->with('competences')
            ->orderBy('date_projet', 'desc')
            ->get();

        if ($projects->isNotEmpty()) {
            return $projects;
        }

        return $this->buildDemoProjects();
    }

    public function getCompetences()
    {
        $competences = Competence::query()->orderBy('nom')->get();

        if ($competences->isNotEmpty()) {
            return $competences;
        }

        return $this->buildDemoCompetences();
    }

    public function getExperiences()
    {
        $experiences = Experience::query()->orderBy('date_debut', 'desc')->get();

        if ($experiences->isNotEmpty()) {
            return $experiences;
        }

        return new Collection([
            new Experience([
                'id' => 'exp-1',
                'titre' => 'Lead Frontend Developer',
                'entreprise' => 'Nova Studio',
                'lieu' => 'Paris',
                'contact_entreprise' => 'hello@novastudio.fr',
                'date_debut' => '2023-01-01',
                'date_fin' => null,
                'bilan' => [
                    'Direction de la refonte front d’un produit SaaS B2B.',
                    'Mise en place d’une architecture UI plus maintenable et performante.',
                    'Coordination avec design, product et backend pour accélérer la livraison.',
                ],
            ]),
            new Experience([
                'id' => 'exp-2',
                'titre' => 'Fullstack Developer',
                'entreprise' => 'Metrik Lab',
                'lieu' => 'Lyon',
                'contact_entreprise' => 'contact@metriklab.dev',
                'date_debut' => '2021-04-01',
                'date_fin' => '2022-12-31',
                'bilan' => [
                    'Développement d’API et d’outils internes en Laravel et Vue.',
                    'Optimisation des flux de données et réduction du temps de traitement.',
                    'Participation à la mise en production de plusieurs modules métier.',
                ],
            ]),
        ]);
    }

    public function getFormations()
    {
        $formations = Formation::query()->orderBy('date_debut', 'desc')->get();

        if ($formations->isNotEmpty()) {
            return $formations;
        }

        return new Collection([
            new Formation([
                'id' => 'edu-1',
                'nom' => 'Master Informatique - Développement Logiciel',
                'institut' => 'Université Paris-Saclay',
                'domaine' => 'Software Engineering',
                'diplome' => 'Master',
                'description' => 'Spécialisation développement de produits et architecture logicielle.',
                'date_debut' => '2018-09-01',
                'date_fin' => '2020-06-30',
                'justificatif' => null,
            ]),
            new Formation([
                'id' => 'edu-2',
                'nom' => 'Bachelor Web & UX',
                'institut' => 'Ecole de Design & Technologie',
                'domaine' => 'UX / Web',
                'diplome' => 'Bachelor',
                'description' => 'Approche produit, UX et développement web.',
                'date_debut' => '2015-09-01',
                'date_fin' => null,
                'justificatif' => null,
            ]),
        ]);
    }

    public function getMessages()
    {
        return Message::query()->orderBy('created_at', 'desc')->get();
    }

    public function getLatestUnreadMessages(int $limit = 3)
    {
        return Message::query()
            ->where('lu', false)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getUnreadMessagesCount(): int
    {
        return Message::query()->where('lu', false)->count();
    }

    private function buildDemoCompetences(): Collection
    {
        return new Collection([
            new Competence(['id' => 'skill-1', 'nom' => 'Laravel', 'categorie' => 'backend', 'niveau' => 'expert']),
            new Competence(['id' => 'skill-2', 'nom' => 'Vue.js', 'categorie' => 'frontend', 'niveau' => 'avancé']),
            new Competence(['id' => 'skill-3', 'nom' => 'Tailwind CSS', 'categorie' => 'frontend', 'niveau' => 'avancé']),
            new Competence(['id' => 'skill-4', 'nom' => 'MySQL', 'categorie' => 'backend', 'niveau' => 'avancé']),
            new Competence(['id' => 'skill-5', 'nom' => 'API REST', 'categorie' => 'backend', 'niveau' => 'expert']),
        ]);
    }

    private function buildDemoProjects(): Collection
    {
        $competences = $this->buildDemoCompetences();

        $projectOne = new Project([
            'id' => 'project-1',
            'titre' => 'Plateforme de gestion de portfolio',
            'description' => 'Une solution complète pour présenter des projets, gérer des compétences et centraliser les contenus de portfolio.',
            'fonctionnalites' => [
                'Dashboard d’administration',
                'Gestion des compétences',
                'Publications de projets',
            ],
            'role' => 'Lead developer',
            'image_principale' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80',
            'captures' => [],
            'github_url' => 'https://github.com',
            'url_projet' => 'https://example.com',
            'date_projet' => '2025-02-01',
            'publie' => true,
        ]);
        $projectOne->setRelation('competences', new Collection([$competences[0], $competences[2], $competences[4]]));

        $projectTwo = new Project([
            'id' => 'project-2',
            'titre' => 'Application de suivi d’équipe',
            'description' => 'Un outil interne de suivi des tâches, planification et reporting pour les équipes produit.',
            'fonctionnalites' => [
                'Tableau de bord',
                'Suivi des tâches',
                'Notifications collaboratives',
            ],
            'role' => 'Fullstack developer',
            'image_principale' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=80',
            'captures' => [],
            'github_url' => 'https://github.com',
            'url_projet' => 'https://example.com',
            'date_projet' => '2024-10-15',
            'publie' => true,
        ]);
        $projectTwo->setRelation('competences', new Collection([$competences[0], $competences[1], $competences[3]]));

        $projectThree = new Project([
            'id' => 'project-3',
            'titre' => 'Marketplace B2B',
            'description' => 'Une marketplace dédiée à la mise en relation entre fournisseurs et clients, pensée autour d’un parcours rapide et fiable.',
            'fonctionnalites' => [
                'Catalogue produits',
                'Paiement et facturation',
                'Gestion des vendeurs',
            ],
            'role' => 'Backend engineer',
            'image_principale' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
            'captures' => [],
            'github_url' => 'https://github.com',
            'url_projet' => 'https://example.com',
            'date_projet' => '2024-04-01',
            'publie' => true,
        ]);
        $projectThree->setRelation('competences', new Collection([$competences[0], $competences[3], $competences[4]]));

        return new Collection([$projectOne, $projectTwo, $projectThree]);
    }
}
