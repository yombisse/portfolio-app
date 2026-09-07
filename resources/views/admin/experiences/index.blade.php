@extends('layouts.admin')

@section('title', 'Expériences')

@section('content')
    <div class="admin-page-header">
        <div>
            <p class="eyebrow eyebrow-admin">Contenu</p>
            <h1>Expériences</h1>
        </div>
        <a href="{{ route('admin.experiences.create') }}" class="btn btn-primary">Ajouter une expérience</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="admin-panel">
        @if($experiences->isEmpty())
            <p>Aucune expérience enregistrée.</p>
        @else
            <div class="space-y-4">
                @foreach($experiences as $experience)
                    <article class="message-item">
                        <div class="message-head">
                            <div>
                                <strong>{{ $experience->titre }}</strong>
                                <p class="message-subject">{{ $experience->entreprise }}</p>
                            </div>
                            <span>{{ $experience->date_debut->format('m/Y') }} - {{ $experience->date_fin?->format('m/Y') ?? 'Aujourd’hui' }}</span>
                        </div>
                        @if($experience->lieu)<p class="mt-2">{{ $experience->lieu }}</p>@endif
                        <p class="mt-2 text-sm text-slate-400">{{ count($experience->bilan ?? []) }} élément(s) dans le bilan</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('admin.experiences.edit', $experience) }}" class="btn btn-primary">Modifier</a>
                            <form method="POST" action="{{ route('admin.experiences.destroy', $experience) }}" onsubmit="return confirm('Supprimer cette expérience ?');">
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
