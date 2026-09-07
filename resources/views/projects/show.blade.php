@extends('layouts.app')

@section('title', $project->titre)

@section('content')
    <section class="section">
        <div class="container project-detail">
            <a href="{{ route('projects.index') }}" class="back-link">← Retour aux projets</a>

            <div class="project-hero">
                <div>
                    <p class="eyebrow">Projet</p>
                    <h1>{{ $project->titre }}</h1>
                    <p class="project-role">{{ $project->role }}</p>
                </div>
                @if($project->image_principale)
                    <img src="{{ str_starts_with($project->image_principale, 'http') ? $project->image_principale : asset('storage/' . ltrim($project->image_principale, '/')) }}" alt="{{ $project->titre }}" class="project-main-image">
                @endif
            </div>

            <div class="project-body">
                <div>
                    <h2>Description</h2>
                    <p>{{ $project->description }}</p>
                </div>

                @if($project->fonctionnalites)
                    <div>
                        <h2>Fonctionnalités</h2>
                        <ul class="bullet-list">
                            @foreach($project->fonctionnalites as $fonctionnalite)
                                <li>{{ $fonctionnalite }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($project->competences->isNotEmpty())
                    <div>
                        <h2>Technologies</h2>
                        <div class="chip-list">
                            @foreach($project->competences as $competence)
                                <span class="chip">{{ $competence->nom }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($project->captures)
                    <div>
                        <h2>Captures</h2>
                        <div class="gallery-grid">
                            @foreach($project->captures as $capture)
                                <img src="{{ str_starts_with($capture, 'http') ? $capture : asset('storage/' . ltrim($capture, '/')) }}" alt="Capture du projet {{ $project->titre }}" class="gallery-image" loading="lazy">
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="project-links">
                    @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn btn-primary">GitHub</a>
                    @endif
                    @if($project->url_projet)
                        <a href="{{ $project->url_projet }}" target="_blank" rel="noopener" class="btn btn-secondary">Voir le projet</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
