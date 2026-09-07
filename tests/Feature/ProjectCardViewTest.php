<?php

namespace Tests\Feature;

use App\Models\Project;
use Tests\TestCase;

class ProjectCardViewTest extends TestCase
{
    public function test_project_card_renders_without_crashing_when_id_is_missing(): void
    {
        $project = new Project([
            'titre' => 'Projet test',
            'description' => 'Description du projet test',
            'role' => 'Développeur',
            'date_projet' => now(),
            'publie' => true,
        ]);

        $project->id = null;

        $html = view('components.project-card', ['project' => $project])->render();

        $this->assertStringContainsString('Projet test', $html);
        $this->assertStringNotContainsString('route(', $html);
    }
}
