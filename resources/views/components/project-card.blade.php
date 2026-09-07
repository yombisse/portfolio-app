<article class="project-card">
    <div class="project-card-image">
        @if($project->image_principale)
            <img src="{{ str_starts_with($project->image_principale, 'http') ? $project->image_principale : asset('storage/' . ltrim($project->image_principale, '/')) }}" alt="{{ $project->titre }}">
        @else
            <div class="placeholder-image">Projet</div>
        @endif
    </div>

    <div class="project-card-body">
        @if($project->getKey())
            <a href="{{ route('projects.show', $project->getKey()) }}" class="project-card-title-link">
                <h2>{{ $project->titre }}</h2>
            </a>
        @else
            <h2>{{ $project->titre }}</h2>
        @endif
        <p>{{ Str::limit($project->description, 150) }}</p>

        <div class="project-meta">
            <span>{{ $project->role }}</span>
            @if($project->date_projet)
                <span>{{ $project->date_projet->format('d/m/Y') }}</span>
            @endif
        </div>

        <div class="chip-list">
            @foreach($project->competences as $competence)
                <span class="chip">{{ $competence->nom }}</span>
            @endforeach
        </div>

        <div class="project-actions">
            @if($project->getKey())
                <a href="{{ route('projects.show', $project->getKey()) }}" class="btn btn-primary">Voir les détails</a>
            @endif
            @if($project->github_url)
                <a href="{{ $project->github_url }}" target="_blank" rel="noopener">GitHub</a>
            @endif
            @if($project->url_projet)
                <a href="{{ $project->url_projet }}" target="_blank" rel="noopener">Voir le projet</a>
            @endif
        </div>

    </div>
</article>
