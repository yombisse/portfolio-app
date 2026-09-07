@extends('layouts.admin')

@section('title', 'Projets')

@section('content')
    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Contenu</p>
            <h1>Projets</h1>
        </div>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">Ajouter un projet</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="admin-panel">
        @if($projects->isEmpty())
            <p>Aucun projet enregistré.</p>
        @else
            <div class="space-y-4">
                @foreach($projects as $project)
                    <article class="message-item">
                        <div class="message-head">
                            <div class="flex items-center gap-3">
                                <strong>{{ $project->titre }}</strong>
                                <span class="badge">{{ $project->publie ? 'Publié' : 'Brouillon' }}</span>
                            </div>
                            <span>{{ $project->date_projet?->format('d/m/Y') ?? 'Sans date' }}</span>
                        </div>
                        <p class="mt-2">{{ $project->role }}</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-primary">Modifier</a>
                            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Supprimer ce projet ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn border border-rose-500/50 text-rose-300 hover:bg-rose-500/10">Supprimer</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection