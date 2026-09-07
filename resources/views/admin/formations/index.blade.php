@extends('layouts.admin')

@section('title', 'Formations')

@section('content')
    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Contenu</p>
            <h1>Formations</h1>
        </div>
        <a href="{{ route('admin.formations.create') }}" class="btn btn-primary">Ajouter une formation</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="admin-panel">
        @if($formations->isEmpty())
            <p>Aucune formation enregistrée.</p>
        @else
            <div class="space-y-4">
                @foreach($formations as $formation)
                    <article class="message-item">
                        <div class="message-head">
                            <div>
                                <strong>{{ $formation->nom }}</strong>
                                <p class="message-subject">{{ $formation->institut }}</p>
                            </div>
                            <span>{{ $formation->date_debut->format('m/Y') }} - {{ $formation->date_fin?->format('m/Y') ?? 'En cours' }}</span>
                        </div>
                        @if($formation->diplome)<p class="mt-2">{{ $formation->diplome }}</p>@endif
                        @if($formation->domaine)<p class="text-sm text-slate-400">{{ $formation->domaine }}</p>@endif
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('admin.formations.edit', $formation) }}" class="btn btn-primary">Modifier</a>
                            <form method="POST" action="{{ route('admin.formations.destroy', $formation) }}" onsubmit="return confirm('Supprimer cette formation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn border border-rose-500/50 text-rose-300">Supprimer</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
